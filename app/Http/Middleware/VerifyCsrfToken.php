<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    // Tambahkan API endpoints ke excluded URLs
    protected $except = [
        'api/*',  // Semua API endpoints diizinkan tanpa CSRF
    ];
}
