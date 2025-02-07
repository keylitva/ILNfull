<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestAttempt;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class TestControllerSubmit extends Controller
{
    public function submitTest(Request $request)
    {
        // Get the logged-in user
        $user = Auth::user();

        // Get test attempt
        $cheating = $request->input('cheating');
        $timeTaken = $request->input('time_taken');
        //dd($request->all());
        $testAttemptToken = $request->input('test_attempt_token');
        $testAttempt = TestAttempt::where('test_attempt_token', $testAttemptToken)->firstOrFail();

        // Retrieve answers from request
        $answers = $request->input('answers', []);
        
        // Fetch related questions
        $questions = Question::whereIn('question_id', array_keys($answers))->get();

        // Initialize score tracking
        $totalScore = 0;
        $finalAnswers = [];
        $totalAvailableScore = 0;

        foreach ($questions as $question) {
            $questionId = $question->question_id;
            $userAnswer = $answers[$questionId] ?? null; // User's answer
            $correctAnswer = json_decode($question->correct_answer, true); // Correct answer (option_ids)
            $maxScore = (float) $question->max_score;
            $totalAvailableScore += $maxScore;
            $questionScore = 0;

            // **SCORING LOGIC**
            switch ($question->question_type) {
                case 'multiple_choice':
                case 'short_answer':
                    if ($userAnswer == $correctAnswer[0]) {
                        $questionScore = $maxScore;
                        $totalScore += $questionScore;
                    } else {
                        $totalScore += 0;
                    }
                    break;

                case 'true_false':
                    $userAnswer = ($userAnswer == "true");
                    if ($userAnswer == $correctAnswer) {
                        $questionScore = $maxScore;
                        $totalScore += $questionScore;
                    } else {
                        $totalScore += 0;
                    }
                    break;

                case 'fill_in_the_gaps':
                    $correctCount = 0;
                    if (is_array($userAnswer) && is_array($correctAnswer)) {
                        // Get all options for the current question
                        $options = json_decode($question->options, true); // Options JSON

                        // Find the correct option IDs from the options (matching gap_id and option_id)
                        $correctOptionIds = $correctAnswer;

                        $totalGaps = count($correctOptionIds);

                        // Check if user selected the correct option IDs
                        foreach ($userAnswer as $userOptionId) {
                            if (in_array($userOptionId, $correctOptionIds)) {
                                $correctCount++;
                            }
                        }

                        // Calculate the score
                        if ($totalGaps > 0) {
                            $questionScore = ($correctCount / $totalGaps) * $maxScore;
                        }

                        $totalScore += round($questionScore, 1);
                    }
                    //dd($userAnswer, $options, $correctOptionIds, $totalGaps, $correctCount, $maxScore, $questionScore);
                    break;

                case 'essay': 
                    // Teachers will review and assign scores manually
                    $questionScore = 0;
                    break;
            }

            // Store user answers with assigned score
            $finalAnswers[$questionId] = [
                'answer' => $userAnswer,
                'score' => round($questionScore, 1),
            ];
        }

        // Calculate percentage
        $percentage = ($totalScore / $totalAvailableScore) * 100;
        //dd($totalScore, $finalAnswers, $totalAvailableScore, $percentage, $timeTaken);

        // Update test attempt with computed score and answers
        $testAttempt->update([
            'score' => round($percentage, 3),
            'answers' => json_encode($finalAnswers),
            'points_collected' => $totalScore,
            'points_max' => $totalAvailableScore,
            'time_taken' => $timeTaken,
            'updated_at' => now()
        ]);

        // Redirect to results page
        return redirect()->route('test.results', ['testAttempt' => $testAttempt->test_attempt_token])
        ->with('success', 'Test submitted successfully!');
    }
}