<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function(Throwable $exception)
        {
            if ($exception instanceof NotFoundHttpException) {
                return response()->view('errors.404', [], 404);
            }
        
            // Handle Invalid Route Method (405)
            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json(['error' => 'Method not allowed'], 405);
            }
        
            // Handle Model Not Found Exception
            if ($exception instanceof ModelNotFoundException) {
                return response()->view('errors.404', [], 404);
            }

            // Validation Exception
            if ($exception instanceof ValidationException) {
                return redirect()->back()->withErrors($exception->errors())->withInput();
            }
        
            // Database Query Errors
            if ($exception instanceof QueryException) {
                return response()->json(['error' => 'A database error occurred. Please try again later.'], 500);
            }
        });
    }
}
