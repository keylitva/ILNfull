<?php
use App\Models\FetchQuestion;  // Import FetchQuestion model
use App\Models\TestAttempt;    // Import TestAttempt model (if needed)
use App\Models\FetchTest;           // Import Test model (if needed)

class TestController extends Controller
{
    public function showTest($test_id, $test_attempt_token)
    {
            // Fetch the test based on testId
    $test = Test::findOrFail($testId);

    // Fetch the test attempt based on testId (if you have a TestAttempt model)
    $test_attempt = TestAttempt::where('test_id', $testId)->first();

    // Fetch questions and their associated options
    $questions = FetchQuestion::with('options')  // Eager load options with questions
        ->where('test_id', $testId)               // Only get questions related to this test
        ->get();

    // Return the view with the test, test attempt, and questions
    return view('exam', compact('test', 'test_attempt', 'questions'));
    }
}