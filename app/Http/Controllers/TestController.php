<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\FetchQuestion;
use App\Models\TestAttempt;
use App\Models\FetchTest;
use App\Models\Test;
use App\Models\Question;


class TestController extends Controller
{
    public function create()
    {
        // Check if the user is authenticated and has 'teacher' permissions
        if (auth()->check()) {
            if (auth()->user()->permissions === 'teacher' ||  auth()->user()->permissions === 'admin') {
                return view('test.create');
            }
        }

        // Redirect to a different page if the user doesn't meet the condition
        return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
    }

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

    private function generateUniqueTestId()
    {
        do {
            // Generate a 10-digit random number
            $testId = str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);

            // Check if the number already exists in the database
            $existingTestId = DB::table('tests')->where('test_alternative_id', $testId)->exists();
        } while ($existingTestId);  // Repeat until the ID is unique

        return $testId;
    }

    public function store(Request $request)
    {
        //dd($request);
        $validated = $request->validate([
            'test_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit_minutes' => 'required|integer|min:1',
            'is_active' => 'required|integer|min:0|max:1',
            'questions' => 'required|array|min:1',
            'questions.*.type' => 'required|in:fill_in_the_gaps,multiple_choice,short_answer,true_false',
            'questions.*.text' => 'required|string',
            'questions.*.gaps' => 'nullable|array',
            'questions.*.options' => 'nullable|array',
            'questions.*.correct_option' => 'nullable|integer|min:0',
            'questions.*.correct_answer' => 'nullable|string',
        ]);
        //dd($validated['is_active']);
        $test = FetchTest::create([
            'test_name' => $validated['test_name'],
            'description' => $validated['description'] ?? null,
            'time_limit_minutes' => $validated['time_limit_minutes'],
            'is_active' => $validated['is_active'],
            'created_by' => auth()->id(),
            'test_alternative_id' => $this->generateUniqueTestId()
        ]);

        foreach ($validated['questions'] as $questionData) {
            //dd($this->processAnswers($questionData));
            //dd($questionData['type']);
            Question::create([
                'test_id' => $test->test_id, // Accessing the id of the created test
                'question_text' => $questionData['text'],
                'question_type' => $questionData['type'],
                'options' => $this->processOptions($questionData), // Store options as JSON
                'correct_answer' => $this->processAnswers($questionData), // Store correct answers as JSON
                'max_score' => $this->calculateScore($questionData['type']),
            ]);
        }

        return redirect()->route('TestDashboard')->with('success', 'Test created successfully!');
    }

    private function processOptions(array $question): array
    {
        if ($question['type'] !== 'fill_in_the_gaps') {
            return $question['options'] ?? [];
        }

        $options = [];
        if (!empty($question['gaps'])) {
            foreach ($question['gaps'] as $gapId => $gap) {
                if (!empty($gap['options'])) {
                    foreach ($gap['options'] as $idx => $text) {
                        $options[] = [
                            'option_id' => $idx + 1,
                            'option_text' => $text,
                            'input_type' => 'dropdown',
                            'gap_id' => (int)$gapId
                        ];
                    }
                } elseif (!empty($gap['correct_answer'])) {
                    $options[] = [
                        'option_id' => 1,
                        'option_text' => $gap['correct_answer'],
                        'input_type' => 'input',
                        'gap_id' => (int)$gapId
                    ];
                }
            }
        }
        return $options;
    }

    private function processAnswers(array $question): array
    {
        if ($question['type'] === 'multiple_choice') {
            return [$question['options'][$question['correct_option'] - 1] ?? null];
        }

        if ($question['type'] === 'fill_in_the_gaps') {
            $answers = [];
            foreach ($question['gaps'] as $gapId => $gap) {
                $correctAnswer = null;

                // Check if 'correct_option' exists and is a valid index in 'options'
                if (isset($gap['correct_option']) && array_key_exists((int)$gap['correct_option'], $gap['options'])) {
                    $correctAnswer = $gap['options'][(int)$gap['correct_option'] - 1];
                } else {
                    // Use the direct correct answer for input-type gaps
                    $correctAnswer = $gap['correct_answer'] ?? ($gap['options'][count($gap['options']) - 1] ?? null);
                }

                $answers[] = [
                    'gap_id' => (int) $gapId,
                    'answer' => $correctAnswer
                ];
            }
            return $answers;
        }

        return [$question['correct_answer'] ?? null];
    }

    private function calculateScore(string $type): float
    {
        return match ($type) {
            'fill_in_the_gaps' => 5.0,
            'multiple_choice' => 2.0,
            'short_answer' => 3.0,
            'true_false' => 2.0,
            default => 1.0,
        };
    }

    public function edit($test_id)
    {
        $test = FetchTest::findOrFail($test_id);
        $questions = Question::where('test_id', $test_id)->get();
        //dd($test, $questions);
        return view('test.create', compact('test', 'questions'));
    }
    public function update(Request $request, $test_id)
    {
        // Validate incoming data
        $validated = $request->validate([
            'test_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit_minutes' => 'required|integer|min:1',
            'is_active' => 'required|integer|min:0|max:1',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'nullable|exists:questions,question_id', // Optional for new questions
            'questions.*.type' => 'required|in:fill_in_the_gaps,multiple_choice,short_answer,true_false',
            'questions.*.text' => 'required|string',
            'questions.*.gaps' => 'nullable|array',
            'questions.*.options' => 'nullable|array',
            'questions.*.correct_option' => 'nullable|integer|min:0',
            'questions.*.correct_answer' => 'nullable|string',
        ]);
        //dd($validated);
        // Find the test or fail
        $test = FetchTest::findOrFail($test_id);

        // Update test details
        $test->update([
            'test_name' => $validated['test_name'],
            'description' => $validated['description'] ?? null,
            'time_limit_minutes' => $validated['time_limit_minutes'],
            'is_active' => $validated['is_active'],
        ]);

        // Loop through each submitted question and either update or create it
        foreach ($validated['questions'] as $questionData) {
            // If the question has an ID, we update it
            if (isset($questionData['id'])) {
                $question = Question::findOrFail($questionData['id']);
                // Update the existing question
                $question->update([
                    'question_text' => $questionData['text'],
                    'question_type' => $questionData['type'],
                    'options' => $this->processOptions($questionData), // Process options into JSON
                    'correct_answer' => $this->processAnswers($questionData), // Process answers into JSON
                    'max_score' => $this->calculateScore($questionData['type']),
                ]);
            } else {
                // If there's no ID, it's a new question, so we create it
                Question::create([
                    'test_id' => $test->test_id,
                    'question_text' => $questionData['text'],
                    'question_type' => $questionData['type'],
                    'options' => $this->processOptions($questionData), // Process options into JSON
                    'correct_answer' => $this->processAnswers($questionData), // Process answers into JSON
                    'max_score' => $this->calculateScore($questionData['type']),
                ]);
            }
        }

        return redirect()->route('TestDashboard')->with('success', 'Test updated successfully!');
    }
    public function massDelete(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect('/')->withErrors(['error' => 'You are trying to perform a forbidden action.']);
        }

        // Get the selected test IDs from the request
        $testIds = explode(',', $request->input('selected_tests', ''));

        if (count($testIds) > 0) {
            // Fetch tests that are created by the current authenticated user
            $tests = FetchTest::whereIn('test_id', $testIds)
                ->where('created_by', auth()->id())
                ->get();

            // If no tests found or if the tests do not belong to the authenticated user
            if ($tests->isEmpty()) {
                return redirect()->route('TestDashboard')->withErrors(['error' => 'You cannot delete tests you did not create.']);
            }

            // Begin the deletion process
            \DB::beginTransaction();

            try {
                // First, delete related questions
                $relatedQuestions = \DB::table('questions')->whereIn('test_id', $testIds);

                if ($relatedQuestions->count() > 0) {
                    $relatedQuestions->delete(); // Delete related questions
                }

                // Now delete the tests
                FetchTest::whereIn('test_id', $testIds)->where('created_by', auth()->id())->delete();

                // Commit the transaction
                \DB::commit();

                return redirect()->route('TestDashboard')->with('success', 'Tests deleted successfully.');
            } catch (\Exception $e) {
                // Rollback the transaction if something fails
                \DB::rollBack();

                // Optionally, log the error for debugging
                \Log::error('Error deleting tests: ' . $e->getMessage());

                return redirect()->route('TestDashboard')->with('error', 'An error occurred while deleting the tests.');
            }
        }

        // If no test IDs were provided
        return redirect()->route('TestDashboard')->with('error', 'No tests selected for deletion.');
    }

    public function destroy($test_id)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect('/')->withErrors(['error' => 'You are trying to perform a forbidden action.']);
        }

        // Find the test by its ID
        $test = FetchTest::where('test_id', $test_id)->first();

        // Check if the test exists and if the user is the creator of the test
        if ($test && $test->created_by == auth()->id()) {
            // Delete the test
            $test->delete();
            return redirect()->route('TestDashboard')->with('success', 'Test deleted successfully.');
        } else {
            return redirect()->route('TestDashboard')->withErrors(['error' => 'You do not have permission to delete this test.']);
        }
    }

    public function massActivate(Request $request)
    {
        // Check if the user is logged in
        if (!auth()->check()) {
            return redirect('/')->withErrors(['error' => 'You must be logged in to perform this action.']);
        }

        $testIds = explode(',', $request->input('selected_tests', ''));

        if (count($testIds) > 0) {
            // Check if the tests belong to the logged-in user
            $ownedTests = FetchTest::whereIn('test_id', $testIds)
                ->where('created_by', auth()->id())
                ->get();

            if ($ownedTests->count() === 0) {
                return redirect()->route('TestDashboard')->withErrors(['error' => 'You do not own any of the selected tests.']);
            }

            // Proceed with activating the tests
            FetchTest::whereIn('test_id', $testIds)
                ->where('created_by', auth()->id()) // Ensure the user owns the tests
                ->update(['is_active' => 1]);

            return redirect()->route('TestDashboard')->with('success', 'Tests activated successfully.');
        }

        return redirect()->route('TestDashboard')->with('error', 'No tests selected.');
    }

    public function massDeactivate(Request $request)
    {
        // Check if the user is logged in
        if (!auth()->check()) {
            return redirect('/')->withErrors(['error' => 'You must be logged in to perform this action.']);
        }

        $testIds = explode(',', $request->input('selected_tests', ''));

        if (count($testIds) > 0) {
            // Check if the tests belong to the logged-in user
            $ownedTests = FetchTest::whereIn('test_id', $testIds)
                ->where('created_by', auth()->id())
                ->get();

            if ($ownedTests->count() === 0) {
                return redirect()->route('TestDashboard')->withErrors(['error' => 'You do not own any of the selected tests.']);
            }

            // Proceed with deactivating the tests
            FetchTest::whereIn('test_id', $testIds)
                ->where('created_by', auth()->id()) // Ensure the user owns the tests
                ->update(['is_active' => 0]);

            return redirect()->route('TestDashboard')->with('success', 'Tests deactivated successfully.');
        }

        return redirect()->route('TestDashboard')->with('error', 'No tests selected.');
    }
}
