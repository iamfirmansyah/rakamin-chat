<?php

namespace App\Exceptions;

use App\Helpers\Response;
use App\Helpers\SlackMessage;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return Response::send(404, NULL, "URL_NOT_FOUND");
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return Response::send(405);
        }

        if ($exception instanceof QueryException) {
            return Response::send(500, null, $exception->getMessage());
        }

        if ($exception instanceof TooManyRequestsHttpException) {
            return Response::send(429, null, 'TO_MANY_REQUEST');
        }

        if ($exception instanceof Throwable) {
            return Response::send(500, null, $exception->getMessage());
        }

        return Response::send(500, null, $exception->getMessage());
    }
}
