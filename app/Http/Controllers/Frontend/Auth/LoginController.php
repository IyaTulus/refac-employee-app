<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Captcha tidak valid.',
                    'errors' => [
                        'captcha' => ['Captcha tidak valid.'],
                    ],
                ], 422);
            }

            return back()->withErrors(['captcha' => 'Captcha tidak valid'])->withInput($request->except('password'));
        }

        $authenticated = Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        );

        if (!$authenticated) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Email atau password salah.',
                    'errors' => [
                        'email' => ['Email atau password salah.'],
                    ],
                ], 422);
            }

            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput($request->except('password'));
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil login.',
                'redirect_url' => Redirect::intended(route('backend.home.index'))->getTargetUrl(),
            ]);
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
