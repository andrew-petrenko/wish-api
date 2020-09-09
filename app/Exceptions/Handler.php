<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;
use WishApp\Model\Exception\ModelNotFoundException;
use WishApp\Service\Auth\Exception\EmailAlreadyInUseException;
use WishApp\Service\Auth\Exception\InvalidPasswordException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } elseif ($e instanceof ModelNotFoundException) {
            return \response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } elseif ($e instanceof InvalidPasswordException
            || $e instanceof \InvalidArgumentException
            || $e instanceof EmailAlreadyInUseException
        ) {
            return \response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return \response()->json([
            'message' => $e->getMessage(),
            'previous' => $e->getTrace()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
