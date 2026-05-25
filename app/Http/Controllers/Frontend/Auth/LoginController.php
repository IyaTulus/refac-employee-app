<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use jeemce\captcha\helpers\Captcha;

class LoginController extends Controller
{
    public function create()
    {
        return view('frontend.auth.login');
    }

    public function store(LoginRequest $request)
    {
        $captcha = new Captcha();

        if (! $captcha->validate($request->input('captcha'))) {
            return back()->withErrors(['captcha' => 'Captcha tidak valid']);
        }

        $authenticated = Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        );

        if (!$authenticated) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        return redirect()->intended(route('backend.home.index'))->with('success', 'Berhasil login');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
