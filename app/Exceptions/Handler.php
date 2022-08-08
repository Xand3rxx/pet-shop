<?php

namespace App\Exceptions;

use Throwable;
use Psr\Log\LogLevel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\RequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Throw ModelNotFoundException with route model binding on API calls.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => $exception->getModel() == 'App\\Models\\User' ? 'This user does not exist.' : 'This product does not exist.'
                ], 404);
            } else if ($exception instanceof RequestException) {
                return response()->json(['message' => 'External API call failed.'], 500);
            } else if ($exception instanceof NotFoundHttpException) {
                return response()->json(['message' => $exception->getMessage()], 404);
            } else if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json(['message' => $exception->getMessage()], 405);
            } else {
                return response()->json(['message' => 'Token is either invalid or expired.'], 401);
            }
        } else {
            return abort(404);
        }

        return parent::render($request, $exception);
    }
}
