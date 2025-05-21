<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    // ... kode lain ...

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception): Response
    {
        // For API requests, always return JSON response
        if ($request->expectsJson() || $request->is('api/*')) {
            // Get status code from exception or default to 500
            $statusCode = $this->isHttpException($exception) ? $exception->getStatusCode() : ($exception->getCode() > 0 ? $exception->getCode() : 500);

            // Create clean JSON error response without PHP notices
            return response()->json([
                'error' => true,
                'message' => $exception->getMessage() ?: 'Server error',
                'status' => $statusCode
            ], $statusCode);
        }

        return parent::render($request, $exception);
    }
}
