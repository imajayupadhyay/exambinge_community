<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public static function redirectTo(): string
    {
        $user = auth()->user();
        if (!$user) return '/login';

        return $user->onboarded ? '/tweets' : '/onboarding';
    }

    public function boot(): void
    {
        parent::boot();
    }
}
