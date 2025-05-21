<?php

namespace App\Http;

class Kernel extends \Illuminate\Foundation\Http\Kernel
{
    protected $middleware = [
        // ...
        \App\Http\Middleware\Cors::class,
    ];
}
