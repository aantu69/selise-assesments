<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Auth\AuthenticationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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

    // public function render($request, Throwable $exception)
    // {
    //     // if ($request->expectsJson()) {
    //     //     if ($exception instanceof AuthenticationException) {
    //     //         if (view()->exists('errors.' . $exception->getStatusCode())) {
    //     //             return response()->view('errors.' . $exception->getStatusCode(), ['exception' => $exception], $exception->getStatusCode());
    //     //         }
    //     //     }
    //     // }
    //     if ($exception instanceof UnauthorizedException) {
    //         if (view()->exists('errors.' . $exception->getStatusCode())) {
    //             return response()->view('errors.' . $exception->getStatusCode(), ['exception' => $exception], $exception->getStatusCode());
    //         }
    //     }
    // }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->view('errors.401', ['exception' => $exception], 401);
        }

        $guard = Arr::get($exception->guards(), 0);
        switch ($guard) {
            case 'admin':
                $login = 'admin.login';
                break;
            default:
                $login = 'login';
                break;
        }

        return redirect()->guest(route($login));
    }
}
