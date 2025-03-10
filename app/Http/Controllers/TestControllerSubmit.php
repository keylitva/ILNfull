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
    $testAttemptToken = $request->input('test_attempt_token');
    $testAttempt = TestAttempt::where('test_attempt_token', $testAttemptToken)->firstOrFail();

    // Retrieve answers from request
    $answers = $request->input('answers', []);

    // Fetch related questions (if any answers are submitted)
    $questions = !empty($answers) ? Question::whereIn('question_id', array_keys($answers))->get() : collect();

    // Initialize score tracking
    $totalScore = 0;
    $finalAnswers = [];
    $totalAvailableScore = 0;

    if ($questions->isNotEmpty()) {
        foreach ($questions as $question) {
            $questionId = $question->question_id;
            $userAnswer = $answers[$questionId] ?? null; // User's answer
            $correctAnswer = is_string($question->correct_answer) ? json_decode($question->correct_answer, true) : $question->correct_answer;
            $maxScore = (float) $question->max_score;
            $totalAvailableScore += $maxScore;
            $questionScore = 0;

            // **SCORING LOGIC**
            switch ($question->question_type) {
                case 'multiple_choice':
                case 'short_answer':
                    //dd($userAnswer, $correctAnswer[0], $userAnswer == $correctAnswer[0]);
                    if ($userAnswer == $correctAnswer[0]) {
                        $questionScore = $maxScore;
                        $totalScore += $questionScore;
                    }
                    break;

                case 'true_false':
                    $userAnswer = ($userAnswer == "true");
                    if ($userAnswer == $correctAnswer) {
                        $questionScore = $maxScore;
                        $totalScore += $questionScore;
                    }
                    break;

                    case 'fill_in_the_gaps':
                        $correctCount = 0;
                        if (is_array($userAnswer) && is_array($correctAnswer)) {
                            // Extract only the answers from the correctAnswer array for comparison
                            $correctAnswers = array_map(function ($item) {
                                return trim($item['answer']); // Trim whitespace to avoid mismatches
                            }, $correctAnswer);
                    
                            // Map user answers to trimmed strings
                            $userAnswers = array_map('trim', $userAnswer);
                            
                            // Compare user answers with correct answers
                            $totalGaps = count($correctAnswers); // Total number of gaps
                            foreach ($userAnswers as $userOption) {
                                if (in_array($userOption, $correctAnswers, true)) {
                                    $correctCount++; // Increment correct count for each matched answer
                                }
                            }
                            
                            // Calculate the score based on correct answers
                            if ($totalGaps > 0) {
                                $questionScore = ($correctCount / $totalGaps) * $maxScore;
                            }
                            //dd($userAnswers, $correctAnswer, $correctCount, $totalGaps,  $questionScore);
                            // Add the calculated score to the total score
                            $totalScore += round($questionScore, 1);
                        }
                        break;
                case 'essay': 
                    $questionScore = 0; // Teachers will review and assign scores manually
                    break;
            }

            // Store user answers with assigned score
            $finalAnswers[$questionId] = [
                'answer' => $userAnswer,
                'score' => round($questionScore, 1),
            ];
        }
    }

    // **Fix: If no answers were given, ensure totalAvailableScore is set correctly**
    if ($totalAvailableScore == 0) {
        $totalAvailableScore = 1; // Avoid division by zero
    }

    // **Fix: If no answers were submitted, ensure score is 0**
    if (empty($answers)) {
        $totalScore = 0;
    }

    // Calculate percentage
    $percentage = ($totalScore / $totalAvailableScore) * 100;

    // Update test attempt with computed score and answers
    $testAttempt->update([
        'score' => round($percentage, 3),
        'answers' => json_encode($finalAnswers),
        'points_collected' => $totalScore,
        'points_max' => $totalAvailableScore,
        'time_taken' => $timeTaken,
        'updated_at' => now()
    ]);
    
    if ($cheating){
        return redirect()->route('test.results', ['testAttempt' => $testAttempt->test_attempt_token])
        ->with('error', 'You left the test');
    }
    // Redirect to results page
    return redirect()->route('test.results', ['testAttempt' => $testAttempt->test_attempt_token])
        ->with('success', 'Test submitted successfully!');
}
}