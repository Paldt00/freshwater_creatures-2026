<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class PublicPasswordResetController extends Controller
{
    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
        ]);

        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(
                'success',
                'Tautan reset kata sandi sudah dikirim. Silakan cek email Anda.'
            );
        }

        if ($status === Password::INVALID_USER) {
            return back()->with(
                'success',
                'Jika email terdaftar, tautan reset kata sandi akan dikirim ke email tersebut.'
            );
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Tautan reset kata sandi belum bisa dikirim. Silakan coba lagi.',
            ]);
    }

    public function showResetPasswordForm(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => [
                'required',
                'string',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8),
            ],
        ], [
            'token.required' => 'Token reset kata sandi tidak ditemukan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $status = Password::broker('users')->reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('public.login.form')
                ->with(
                    'success',
                    'Kata sandi berhasil diubah. Silakan masuk menggunakan kata sandi baru.'
                );
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => $status === Password::INVALID_TOKEN
                    ? 'Token reset kata sandi tidak valid atau sudah kedaluwarsa.'
                    : 'Reset kata sandi gagal. Silakan coba lagi.',
            ]);
    }
}
