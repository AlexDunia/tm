<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log additional details for API-related exceptions
            if (request()->is('api/*') ||
                request()->is('success*') ||
                request()->is('verifypayment/*'))
            {
                \Log::error('Payment API/verification exception', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An unexpected error occurred.',
                    'error' => $e->getMessage(),
                    'code' => $e instanceof HttpException ? $e->getStatusCode() : 500
                ], $e instanceof HttpException ? $e->getStatusCode() : 500);
            }
        });

        // Add special handling for session token mismatch
        $this->renderable(function (TokenMismatchException $e, $request) {
            // If this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'session_expired',
                    'message' => 'Your session has expired. Please refresh the page.'
                ], 419);
            }

            // Return our simple session expired view for all cases
            return response()->view('session-expired');
        });
    }

    public function render($request, Throwable $exception)
    {
        // AJAX requests should always receive JSON responses for errors
        if ($request->ajax() || $request->wantsJson() ||
            $request->header('X-Requested-With') == 'XMLHttpRequest' ||
            $request->has('is_ajax')) {

            $status = 500;
            $message = 'Server error';

            // Define status code based on exception type
            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                $status = 422;
                $message = 'Validation failed';
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => $exception->errors()
                ], $status);
            } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                $status = 401;
                $message = 'Unauthenticated';
            } elseif ($exception instanceof \Illuminate\Session\TokenMismatchException) {
                $status = 419;
                $message = 'CSRF token mismatch';
            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $status = $exception->getStatusCode();
                $message = $exception->getMessage() ?: 'HTTP error';
            }

            // Debug info only in development
            $debug = config('app.debug') ? [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ] : null;

            return response()->json([
                'success' => false,
                'message' => $message,
                'debug' => $debug
            ], $status);
        }

        // Handle NotFoundHttpException for non-AJAX requests
        if ($exception instanceof NotFoundHttpException) {
            // You can customize the home route name or URL as needed
            return redirect()->route('logg');
        }

        // Default Laravel handling for other non-AJAX requests
        return parent::render($request, $exception);
    }

}
