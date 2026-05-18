<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    })->name('login.attempt');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Registrasi berhasil. Silakan login.');
    })->name('register.store');

    Route::post('/forgot-password', function (Request $request) {
        $validated = $request->validate([
            'fp_name' => ['required', 'string', 'max:150'],
            'fp_email' => ['required', 'email', 'max:191'],
            'fp_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::query()
            ->where('email', $validated['fp_email'])
            ->where('name', $validated['fp_name'])
            ->first();

        if (!$user) {
            return back()
                ->withErrors(['fp_email' => 'Akun tidak ditemukan. Pastikan nama dan email benar.'])
                ->withInput();
        }

        $user->forceFill([
            'password' => Hash::make($validated['fp_password']),
        ])->save();

        return redirect()
            ->route('login')
            ->with('status', 'Password berhasil diubah. Silakan login.');
    })->name('password.forgot');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/history', function () {
        return view('history.index');
    })->name('history');
});
