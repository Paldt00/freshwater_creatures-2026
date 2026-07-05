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
        if (Auth::check()) {
            return $this->redirectAfterLogin();
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        if (! Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'email' => 'Email atau kata sandi tidak sesuai.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->redirectAfterLogin('Berhasil Login');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin();
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
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
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,
        ]);

        $user->assignRole('user');

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with('success', 'Berhasil Registrasi');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Berhasil Logout');
    }

    private function redirectAfterLogin(
        ?string $message = null
    ): RedirectResponse {
        $user = Auth::user();

        if (
            $user instanceof User
            && ($user->is_admin || $user->hasRole('super_admin'))
        ) {
            $redirect = redirect('/admin');
        } else {
            $redirect = redirect()->route('home');
        }

        if ($message !== null) {
            $redirect->with('success', $message);
        }

        return $redirect;
    }
}
