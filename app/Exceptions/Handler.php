<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Psy\Exception\ErrorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that should not be reported.
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
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
        if ($exception instanceof ModelNotFoundException) {
            $model = class_basename($exception->getModel());
            return $this->errorResponse("Does not exits any instance of {$model} with the given", 404);
        }
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessages(), 403);
        }
        if ($exception instanceof AuthenticationException) {
            return $this->errorResponse("Please login again", 401);
        }
        if ($exception instanceof ValidationException) {

            return $this->errorResponse($exception->validator->errors()->getMessages(), 414);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {

            return $this->errorResponse("Method not allowed", 405);
        }
        if ($exception instanceof QueryException) {
            return $this->errorResponse($exception->getMessage(), 400);
        }
        if ($exception instanceof TokenInvalidException) {
            return $this->errorResponse("Token is invalid", 463);
        }
        if ($exception instanceof TokenExpiredException) {
            return $this->errorResponse("Token expired", 406);
        }
        if ($exception instanceof JWTException) {
            return $this->errorResponse($exception->getMessage(), 410);
        }
        if ($exception instanceof UnauthorizedHttpException) {
            return $this->errorResponse($exception->getMessage(), 406);
        }
        if ($exception instanceof \Psy\Exception\FatalErrorException) {
            return $this->errorResponse($exception->getMessage(), 407);
        }
        if ($exception instanceof MassAssignmentException) {
            return $this->errorResponse($exception->getMessage(), 408);
        }
        if ($exception instanceof ErrorException) {
            return $this->errorResponse($exception->getMessage(), 409);
        }
        if ($exception instanceof \InvalidArgumentException) {
            return $this->errorResponse($exception->getMessage(), 411);
        }
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("URL not found", 415);
        }
        if (env('APP_DEBUG') == false) {
            return parent::render($request, $exception);
        }
        return parent::render($request, $exception);
    }
}
