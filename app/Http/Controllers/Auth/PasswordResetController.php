<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function create()
    {
        return redirect()->route('login');
    }

    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))->with('open_modal', 'forgot-password-form')
            : back()->withErrors(['email' => __($status)])->with('open_modal', 'forgot-password-form');
    }

    // Renders welcome.blade.php directly passing the reset token and email
    public function edit(Request $request, $token)
    {
        return view('welcome', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Commits the password update and redirects to login modal on success
    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)])
                    ->withInput($request->only('email', 'token'))
                    ->with('open_modal', 'reset-password-form');
    }
}