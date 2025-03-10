<?php

namespace App\Http\Controllers;

use App\Models\TestAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use OpenAI;

class TestResultsController extends Controller
{
    // Display results for a specific test
    public function showResults($test_id)
    {
        // Fetch attempts with necessary relationships
        $attempts = TestAttempt::where('test_id', $test_id)
            ->with(['user', 'test.questions'])
            ->get();

        // Prepare data structure
        $resultData = [
            'test' => null,
            'attempts' => $attempts,
            'questionData' => ['labels' => [], 'percentages' => [], 'questions' => []],  // Add 'questions' to store full question data
            'studentData' => ['labels' => [], 'scores' => []],
            'feedback' => 'No attempts recorded yet'
        ];

        if ($attempts->isEmpty()) {
            return view('dashboards.each_test', $resultData);
        }

        // Get test and questions
        $test = $attempts->first()->test;
        $resultData['test'] = $test;

        if ($test && $test->questions->isNotEmpty()) {
            // Calculate question statistics with partial scoring
            $questionStats = [];

            foreach ($attempts as $attempt) {
                $answers = json_decode($attempt->answers, true);

                foreach ($answers as $qId => $answerData) {
                    if (!isset($questionStats[$qId])) {
                        $questionStats[$qId] = [
                            'scoreSum' => 0,
                            'attempts' => 0
                        ];
                    }

                    $questionStats[$qId]['attempts']++;
                    $questionStats[$qId]['scoreSum'] += $answerData['score'];
                }
            }

            $questionIndex = 1;

            // Prepare question data for chart using percentage of points earned
            foreach ($test->questions as $question) {
                $qId = $question->question_id;
                $stats = $questionStats[$qId] ?? ['scoreSum' => 0, 'attempts' => 0];

                // Calculate maximum possible score for the question across attempts
                $maxScorePerAttempt = (float)$question->max_score;
                $totalMaxScore = $stats['attempts'] * $maxScorePerAttempt;

                // Calculate percentage based on accumulated score and total maximum score
                $percentage = $totalMaxScore > 0
                    ? round(($stats['scoreSum'] / $totalMaxScore) * 100, 2)
                    : 0;

                $resultData['questionData']['labels'][] = 'Q' . $questionIndex;
                $resultData['questionData']['percentages'][] = $percentage;

                // Add full question data for AI recommendations
                $resultData['questionData']['questions'][] = [
                    'text' => $question->question_text,  // Assuming the question has 'text' attribute
                    'type' => $question->question_type,  // Assuming the question has 'type' attribute
                    'options' => $question->options,  // Assuming the question has 'options' attribute
                    'correct_answer' => $question->correct_answer,  // Assuming the question has 'correct_answer' attribute
                    'question_id' => $qId
                ];
                $questionIndex++;
            }
        }

        // Prepare student performance data
        foreach ($attempts as $attempt) {
            $resultData['studentData']['labels'][] = $attempt->user->name;
            $resultData['studentData']['scores'][] = (float)$attempt->score;
        }

        // Generate recommendations based on question accuracy
        $resultData['feedback'] = null;

        return view('dashboards.each_test', $resultData);
    }

    private function generateRecommendations($questionData)
    {
        //dd($questionData);
        $recommendations = [];

        // Identify low accuracy questions and collect full data
        $lowAccuracyQuestions = [];
        foreach ($questionData['percentages'] as $index => $percentage) {
            if ($percentage < 60) {
                // Fetch the full question data for low-accuracy questions
                $lowAccuracyQuestions[] = [
                    'question' => $questionData['labels'][$index],
                    'text' => $questionData['questions'][$index]['text'],
                    'type' => $questionData['questions'][$index]['type'],
                    'options' => $questionData['questions'][$index]['options'],
                    'correct_answer' => $questionData['questions'][$index]['correct_answer'],
                    'percentage' => $percentage
                ];
            }
        }

        if (!empty($lowAccuracyQuestions)) {
            // Prepare AI prompt based on low-accuracy questions
            $recommendations[] = 'Focus revision on the following questions with low accuracy:';

            foreach ($lowAccuracyQuestions as $question) {
                $recommendations[] = $this->formatQuestionForAI($question);
            }

            // Get AI-generated feedback for these questions
            $APIfeedback = $this->generateAIFeedback($lowAccuracyQuestions);
        }

        $feedback = $this->styleAIFeedback($APIfeedback);
        return $feedback ?? 'No specific recommendations';
    }

    private function formatQuestionForAI($question)
    {
        if ($question['type'] === 'fill_in_the_gaps') {
            return "Question: {$question['text']} (Type: {$question['type']})\n Context: were you see [gap_x] its were users need to input or select something so you can not pay attention to that";
        } else {
            // Check if options is a string, then decode it to an array if necessary
            if (is_string($question['options'])) {
                $options = json_decode($question['options'], true);  // Decode JSON string to array
            } else {
                $options = $question['options'];  // Use as is if already an array
            }

            // Ensure that options is an array
            if (!is_array($options)) {
                $options = [];
            }

            // Flatten any nested arrays (if they exist) and convert all items to strings
            $options = array_map(function ($option) {
                return is_array($option) ? implode(', ', $option) : strval($option);
            }, $options);

            // Return the formatted question
            return "Question: {$question['text']} (Type: {$question['type']})\nOptions: " . implode(', ', $options);
        }
    }

    private function styleAIFeedback($feedback)
    {
        // Remove Markdown formatting
        $formatted = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $feedback); // Bold text
        $formatted = preg_replace('/\* (.*?)\n/', '<li>$1</li>', $formatted); // List items

        // Wrap sections with headings
        $formatted = preg_replace('/^### (.*?)\n/m', '<h3 class="text-lg font-semibold mt-4">$1</h3>', $formatted); // H3 headings
        $formatted = preg_replace('/^#### (.*?)\n/m', '<h4 class="text-md font-medium mt-2">$1</h4>', $formatted); // H4 headings

        // Convert remaining list items into proper lists
        $formatted = preg_replace('/(<li>.*?<\/li>)/s', '<ul class="list-disc list-inside space-y-1">$1</ul>', $formatted);

        // Clean up any unnecessary new lines
        $formatted = nl2br($formatted); // Convert line breaks to <br>

        return '<div class="text-gray-300">' . $formatted . '</div>';
    }

    private function generateAIFeedback($lowAccuracyQuestions)
    {
        // Prepare the AI prompt
        $prompt = "The following questions have low accuracy:\n";
        //dd($lowAccuracyQuestions);
        foreach ($lowAccuracyQuestions as $question) {
            $prompt .= $this->formatQuestionForAI($question) . "\n\n";
        }

        $prompt .= "Provide detailed feedback and study recommendations for the above questions.";
        //dd($prompt);
        // Call OpenAI API to get feedback
        $response = $this->callGeminiAPI($prompt);

        //dd($response);
        return $response;
    }

    private function callGeminiAPI($prompt)
    {
        $apiKey = 'AIzaSyAI0bKyPX1LiscyqVOJ2HE7WRSXn_Wu82o'; // Replace with your actual API key
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";

        $instructions = "Provide friendly, constructive feedback to help teachers understand what they need to teach, what helps in teaching (what games tricks and so on) for the students to do better next time on these questions. Use clear explanations with examples if needed. Keep each point short, no more than 25-30 words. Offer practical suggestions for improvement without being too critical. For fill-in-the-gap questions, focus on the gap text and offer clear guidance. Use bullet points for clarity and avoid technical jargon. Keep the tone supportive and encouraging.";
        // Prepare the request payload
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $instructions . "\n\n" . $prompt]
                    ]
                ]
            ]
        ];

        try {
            // Initialize cURL
            $ch = curl_init($url);

            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            // Execute the request
            $response = curl_exec($ch);
            curl_close($ch);

            // Decode the response
            $responseBody = json_decode($response, true);
            //dd($responseBody);
            // Extract only the feedback text
            if (isset($responseBody['candidates'][0]['content']['parts'][0]['text'])) {
                return $responseBody['candidates'][0]['content']['parts'][0]['text'];
            } else {
                return 'No feedback found in the response.';
            }
        } catch (Exception $e) {
            // Handle errors
            return 'Error: ' . $e->getMessage();
        }
    }

    // Call this method to show the test results and provide AI feedbac
    // View a specific attempt by a student
    public function viewAttempt($test_id, $attempt_id)
    {
        $attempt = TestAttempt::where('test_id', $test_id)
            ->where('attempt_id', $attempt_id)
            ->with('user')
            ->firstOrFail();

        return view('dashboards.each_test_student', [
            'attempt' => $attempt
        ]);
    }
    public function getAIFeedback($test_id)
    {
        // Fetch attempts with necessary relationships
        $attempts = TestAttempt::where('test_id', $test_id)
            ->with(['test.questions'])
            ->get();

        if ($attempts->isEmpty()) {
            return response()->json(['feedback' => 'No attempts available for analysis']);
        }

        // Prepare question data structure
        $questionData = [
            'labels' => [],
            'percentages' => [],
            'questions' => []
        ];

        // Calculate question statistics (same logic as showResults)
        $questionStats = [];
        $test = $attempts->first()->test;

        foreach ($attempts as $attempt) {
            $answers = json_decode($attempt->answers, true);

            foreach ($answers as $qId => $answerData) {
                if (!isset($questionStats[$qId])) {
                    $questionStats[$qId] = [
                        'scoreSum' => 0,
                        'attempts' => 0
                    ];
                }

                $questionStats[$qId]['attempts']++;
                $questionStats[$qId]['scoreSum'] += $answerData['score'];
            }
        }

        // Process questions and calculate percentages
        $questionIndex = 1;
        foreach ($test->questions as $question) {
            $qId = $question->question_id;
            $stats = $questionStats[$qId] ?? ['scoreSum' => 0, 'attempts' => 0];

            $maxScorePerAttempt = (float)$question->max_score;
            $totalMaxScore = $stats['attempts'] * $maxScorePerAttempt;

            $percentage = $totalMaxScore > 0
                ? round(($stats['scoreSum'] / $totalMaxScore) * 100, 2)
                : 0;

            $questionData['labels'][] = 'Q' . $questionIndex;
            $questionData['percentages'][] = $percentage;
            $questionData['questions'][] = [
                'text' => $question->question_text,
                'type' => $question->question_type,
                'options' => $question->options,
                'correct_answer' => $question->correct_answer,
                'question_id' => $qId
            ];

            $questionIndex++;
        }

        // Generate recommendations
        $feedback = $this->generateRecommendations($questionData);

        return response()->json([
            'feedback' => $feedback
        ]);
    }
}
