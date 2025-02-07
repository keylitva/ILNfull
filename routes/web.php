<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\FetchTest;
use App\Models\TestAttempt;
use App\Models\FetchQuestion;
use App\Models\FetchQuestionOption;
use Illuminate\Support\Facades\Hash;

require base_path('/routes/test.web.php');

Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/help', function () {
    return view('help');
});

Route::get('/help', function () {
    return view('help');
});

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/settings', function () {
    return view('settings');
})->name('settings');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/logout', function () {
    auth()->logout(); 
    return redirect('/');
})->name('logout');

Route::post('/login', function () {
    $email = request('email');
    $password = request('password');
    $user = User::where('email', $email)->first();
    $hp = Hash::make($password);
    if ($user && Hash::check($password, $user->password)) {
        auth()->login($user);
        return redirect('/');
    } else {
        return back()->withErrors(['error' => 'Invalid credentials.']);
    }

})->name('loginbackend');
