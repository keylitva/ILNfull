<?php

namespace App\Http\Controllers;

use App\Models\FetchTest;
use App\Models\TestAttempt;
use App\Models\FetchQuestion;
use App\Models\FetchQuestionOption;

class TestController extends Controller
{
    public function showTest($test_id, $test_attempt_token)
    {
        // Fetch the test and test attempt
        $test = FetchTest::find($test_id);
        $test_attempt = TestAttempt::find($test_attempt_token);

        if (!$test_attempt) {
            abort(404, 'Test attempt not found.');
        }

        // Extract the question IDs from the `questions` column
        $question_ids = explode(';', $test_attempt->questions);

        // Fetch the questions
        $questions = FetchQuestion::whereIn('question_id', $question_ids)->get();

        // Attach options to each question
        foreach ($questions as $question) {
            $question->options = FetchQuestionOption::where('question_id', $question->question_id)->get();
        }
        dd($test, $questions, $test_attempt);
        // Return the view with data
        return view('testing', [
            'test' => $test,
            'questions' => $questions,
            'test_attempt' => $test_attempt,
        ]);
    }
}
