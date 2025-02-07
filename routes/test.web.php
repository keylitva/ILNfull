<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestControllerSubmit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\FetchTest;
use App\Models\TestAttempt;
use App\Models\FetchQuestion;
use App\Models\FetchQuestionOption;

Route::get('/results/{testAttempt}', function ($test_token) {

    if (!Auth::check()) {
        return redirect('/')->withErrors(['error' => 'You must log in to access the test.']);
    }
    $user = Auth::user();
    $testAttempt = TestAttempt::where('test_attempt_token', $test_token)->first();
    return view('test.results', ['testAttempt' => $testAttempt]);
})->name('test.results');

Route::get('/test', function () {
    return view('test.search_for_test');
})->name('test.search');

Route::get('/test/{test_id}', function ($test_code) {

    if (!Auth::check()) {
        return redirect('/')->withErrors(['error' => 'You must log in to access the test.']);
    }
    $user = Auth::user();
    $test = FetchTest::where('test_alternative_id', $test_code)->first();
    return view('test.test_info_before_the_test', ['test' => $test]);
});


Route::get('/test/{test_id}/{test_attempt_token}', function ($test_id, $test_attempt_token) {

    if (!Auth::check()) {
        return redirect('/')->withErrors(['error' => 'You must log in to access the test.']);
    }
    $user = Auth::user();
    // Fetch the test and test attempt
    $test = FetchTest::find($test_id);
    $test_attempt = TestAttempt::where('test_attempt_token', $test_attempt_token)->first();

    if (!$test_attempt) {
        abort(404, 'Test attempt not found.');
    }

    // Fetch the questions for the test, now including the 'answers' field directly from the 'questions' table
    $questions = DB::table('questions')
                    ->where('test_id', $test_id)
                    ->get();

    // Prepare the questions with dynamic options based on the question type
    $questionsWithOptions = $questions->map(function ($question) {
        return $question;
    })->shuffle();


    //dd($test, $questionsWithOptions, $test_attempt);
    // Return the view with the questions and options
    return view('test.the_test_itself', ['test' => $test, 'test_attempt' => $test_attempt, 'questions' => $questionsWithOptions]);
});

Route::get('/tests/dashboard', function () {
    if (!Auth::check()) {
        return redirect('/login')->withErrors(['error' => 'You are not allowed to be there please login.']);
    }
    $tests = FetchTest::where('created_by', auth()->id())
        ->withCount(['attempts as attempts_count'])
        ->withAvg('attempts', 'score')
        ->with(['attempts' => function($query) {
            $query->latest()->limit(1);
        }])
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($test) {
            return [
                'id' => $test->test_id,
                'test_alternative_id' => $test->test_alternative_id,
                'test_name' => $test->test_name,
                'description' => $test->description,
                'time_limit_minutes' => $test->time_limit_minutes,
                'is_active' => $test->is_active,
                'attempts_count' => $test->attempts_count,
                'average_score' => (float) number_format($test->attempts_avg_score, 1),
                'last_attempt' => $test->attempts->first()->attempt_date ?? null
            ];
        });
      //dd($tests);
    return view('test.users_created_tests', [
        'tests' => $tests
    ]);
})->name('TestDashboard')->middleware('auth');

Route::post('/tests/delete/{test}/', function ($test_id) {
    if (!Auth::check()) {
        return redirect('/')->withErrors(['error' => 'You are trying to perform a forbiden action.']);
    }
    $test = FetchTest::where('test_id', $test_id)->get();
    if ($test[0]->created_by == auth()->id()) {
        
        $test[0]->delete();
        return redirect()->route('TestDashboard')->with('success', 'Test deleted successfully');
    }
})->name('test.destroy');

Route::post('/search_test', function () {
    $test_code = request('test_code');
    $test_user = request('test_user');

    // Check if user exists
    $user = User::where('alternative_id', $test_user)->first();

    // Check if test exists
    $test = FetchTest::where('test_alternative_id', $test_code)->first();

    // Validate both user and test
    if ($user && $test) {
        // Log in the user manually without requiring a password
        Auth::login($user);

        // Redirect to the test route
        return redirect("/test/{$test_code}");
    }

    // If validation fails, return an error message or redirect back
    return back()->withErrors([
        'error' => 'Invalid test code or user.',
    ]);
});

Route::post('/start_test', function () {
    if (!Auth::check()) {
        return redirect('/')->withErrors(['error' => 'You must log in to access the test.']);
    }
    $user = Auth::user();
    $test = request('test');
    if ($test && $user) {
        // Create a new test attempt
        $test_attempt = new TestAttempt();
        $test_attempt->test_id = $test;
        $test_attempt->user_id = $user->id;
        $test_attempt->test_attempt_token = bin2hex(random_bytes(16));
        $test_attempt->attempt_date = now();
        $test_attempt->question_order = '1;4;2;5;3';
        $test_attempt->save();

        // Redirect to the test route
        return redirect("/test/{$test}/{$test_attempt->test_attempt_token}");
    }
    dd($test, $test_user);
    // If validation fails, return an error message or redirect back
    return back()->withErrors([
        'error' => 'Invalid test code or user.',
    ]);
});

Route::post('/submit_test', [TestControllerSubmit::class, 'submitTest'])->name('submit.test');