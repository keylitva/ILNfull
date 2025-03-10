<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\ProfilePictureController;
use App\Http\Controllers\TestResultsController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\MainDashboard;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

require base_path('/routes/test.web.php');

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
});

Route::get('/help', function () {
    return view('help');
});

Route::get('/help', function () {
    return view('help');
});

Route::get('/settings', function () {
    return view('settings');
})->name('settings');


// auth routes

Route::get('/register', [RegisterUserController::class, 'create'])->name('register');
Route::post('/register', [RegisterUserController::class, 'store']);

Route::get('/login', [LoginUserController::class, 'create'])->name('login');
Route::post('/login', [LoginUserController::class, 'store'])->name('loginbackend');

Route::post('/logout', [LoginUserController::class, 'destroy'])->name('logout');

// misc

Route::get('/profile-picture/{name}', [ProfilePictureController::class, 'generate']);

// Main Dashboard Route
Route::get('/dashboard', [MainDashboard::class, 'create'])->name('dashboard')->middleware('auth');

// routes for test creation/edit
Route::get('/tests/create', [TestController::class, 'create'])->name('tests.create');
Route::post('/tests/store', [TestController::class, 'store'])->name('tests.store');

//test update 
Route::get('/tests/edit/{test_id}', [TestController::class, 'edit'])->name('test.edit');
Route::post('/tests/store/{test_id}', [TestController::class, 'update'])->name('test.update');

//test manipulation
Route::delete('/tests/mass-delete', [TestController::class, 'massDelete'])->name('test.massDelete')->middleware('auth');
Route::post('/tests/delete/{test}', [TestController::class, 'destroy'])->name('test.destroy')->middleware('auth');
Route::post('/tests/mass-activate', [TestController::class, 'massActivate'])->name('test.massActivate')->middleware('auth');
Route::post('/tests/mass-deactivate', [TestController::class, 'massDeactivate'])->name('test.massDeactivate')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Route for viewing results of a specific test
    Route::get('/tests/results/{test_id}', [TestResultsController::class, 'showResults'])
        ->name('dashbords.each_test');
    
    // Route for viewing an individual student's attempt results
    Route::get('/tests/attempt/{test_id}/{attempt_id}', [TestResultsController::class, 'viewAttempt'])
        ->name('dashbords.each_test_student');
});

Route::get('/tests/{test_id}/ai-feedback', [TestResultsController::class, 'getAIFeedback'])->name('test.ai-feedback');