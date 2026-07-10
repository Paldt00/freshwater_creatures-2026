<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class RateLimitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configurePublicLoginRateLimiter();
        $this->configurePublicRegisterRateLimiter();
        $this->configureSearchSuggestionRateLimiter();
    }

    private function configurePublicLoginRateLimiter(): void
    {
        RateLimiter::for(
            'public-login',
            function (Request $request): Limit {
                $email = Str::lower(
                    trim(
                        (string) $request->input(
                            'email',
                            ''
                        )
                    )
                );

                $identity = $email !== ''
                    ? $email
                    : 'unknown-email';

                return Limit::perMinute(3)
                    ->by(
                        'public-login:'
                        . $identity
                        . '|'
                        . $request->ip()
                    )
                    ->response(
                        function (
                            Request $request,
                            array $headers
                        ) {
                            $response = back()
                                ->withErrors([
                                    'email' =>
                                        'Terlalu banyak percobaan masuk. Silakan tunggu 1 menit lalu coba kembali.',
                                ])
                                ->onlyInput('email');

                            foreach (
                                $headers as $name => $value
                            ) {
                                $response->headers->set(
                                    $name,
                                    $value
                                );
                            }

                            return $response;
                        }
                    );
            }
        );
    }

    private function configurePublicRegisterRateLimiter(): void
    {
        RateLimiter::for(
            'public-register',
            function (Request $request): Limit {
                return Limit::perMinute(3)
                    ->by(
                        'public-register:'
                        . $request->ip()
                    )
                    ->response(
                        function (
                            Request $request,
                            array $headers
                        ) {
                            $response = back()
                                ->withErrors([
                                    'email' =>
                                        'Terlalu banyak percobaan pendaftaran. Silakan tunggu 1 menit lalu coba kembali.',
                                ])
                                ->withInput(
                                    $request->only([
                                        'name',
                                        'email',
                                    ])
                                );

                            foreach (
                                $headers as $name => $value
                            ) {
                                $response->headers->set(
                                    $name,
                                    $value
                                );
                            }

                            return $response;
                        }
                    );
            }
        );
    }

    private function configureSearchSuggestionRateLimiter(): void
    {
        RateLimiter::for(
            'search-suggestions',
            function (Request $request): Limit {
                return Limit::perMinute(60)
                    ->by(
                        'search-suggestions:'
                        . $request->ip()
                    )
                    ->response(
                        function (
                            Request $request,
                            array $headers
                        ) {
                            return response()->json(
                                [
                                    'message' =>
                                        'Terlalu banyak permintaan pencarian. Silakan coba kembali beberapa saat lagi.',
                                ],
                                429,
                                $headers
                            );
                        }
                    );
            }
        );
    }
}
