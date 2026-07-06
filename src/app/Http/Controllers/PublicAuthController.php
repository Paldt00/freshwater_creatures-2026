<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PublicAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return $this->redirectAfterLogin();
        }

        return view('auth.login');
    }

    public function login(
        Request $request
    ): RedirectResponse {
        $credentials = $request->validate(
            [
                'email' => [
                    'required',
                    'email',
                ],

                'password' => [
                    'required',
                    'string',
                ],
            ],
            [
                'email.required' =>
                    'Email wajib diisi.',

                'email.email' =>
                    'Format email tidak valid.',

                'password.required' =>
                    'Kata sandi wajib diisi.',
            ]
        );

        $user = User::query()
            ->where(
                'email',
                $credentials['email']
            )
            ->first();

        if (! $user) {
            return back()
                ->withErrors([
                    'email' =>
                        'Akun belum terdaftar. Silakan daftar terlebih dahulu.',
                ])
                ->onlyInput('email');
        }

        $loginSuccessful = Auth::guard('web')
            ->attempt(
                $credentials,
                $request->boolean('remember')
            );

        if (! $loginSuccessful) {
            return back()
                ->withErrors([
                    'password' =>
                        'Kata sandi tidak sesuai.',
                ])
                ->onlyInput('email');
        }

        /*
         * Regenerasi ID session tanpa menghapus
         * session guard admin.
         */
        $request->session()->regenerate();

        return $this->redirectAfterLogin(
            'Berhasil Login'
        );
    }

    public function showRegister():
        View|RedirectResponse {
        if (Auth::guard('web')->check()) {
            return $this->redirectAfterLogin();
        }

        return view('auth.register');
    }

    public function register(
        Request $request
    ): RedirectResponse {
        $data = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ],
            [
                'name.required' =>
                    'Nama wajib diisi.',

                'name.max' =>
                    'Nama maksimal 255 karakter.',

                'email.required' =>
                    'Email wajib diisi.',

                'email.email' =>
                    'Format email tidak valid.',

                'email.max' =>
                    'Email maksimal 255 karakter.',

                'email.unique' =>
                    'Email sudah terdaftar.',

                'password.required' =>
                    'Kata sandi wajib diisi.',

                'password.min' =>
                    'Kata sandi minimal 8 karakter.',

                'password.confirmed' =>
                    'Konfirmasi kata sandi tidak sesuai.',
            ]
        );

        $user = User::query()->create([
            'name' => $data['name'],

            'email' => $data['email'],

            'password' => Hash::make(
                $data['password']
            ),

            'is_admin' => false,
        ]);

        $user->assignRole('user');

        Auth::guard('web')->login($user);

        /*
         * Regenerasi ID session tanpa menghapus
         * session guard admin.
         */
        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with(
                'success',
                'Berhasil Registrasi'
            );
    }

    public function logout(
        Request $request
    ): RedirectResponse {
        /*
         * Hanya logout guard web.
         * Jangan memakai session()->invalidate(),
         * karena dapat menghapus login guard admin.
         */
        Auth::guard('web')->logout();

        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with(
                'success',
                'Berhasil Logout'
            );
    }

    private function redirectAfterLogin(
        ?string $message = null
    ): RedirectResponse {
        /*
         * Login melalui halaman publik tetap diarahkan
         * ke website publik.
         *
         * Login admin dilakukan secara terpisah melalui:
         * /admin/login
         */
        $redirect = redirect()
            ->route('home');

        if ($message !== null) {
            $redirect->with(
                'success',
                $message
            );
        }

        return $redirect;
    }
}
