<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use App\Exceptions\APIResponseException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        APIResponseException::class
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*')|| $request->wantsJson()) {
                return $this->handleApiExceptions($request, $e);
            }
        });

        $this->reportable(function (Throwable $e) {
            
        });
    }

    protected function handleApiExceptions($request, $exception)
    {
        if ($exception instanceof ValidationException) {
            if ($exception->response) {
                return $exception->response;
            }

            return response()->errorResponse('One or more fields are invalid.', $exception->errors(), $exception->status);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));

            return response()->errorResponse("Unable to find any {$modelName} with the specified ID", [], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->errorResponse($exception->getMessage(), [], Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->errorResponse($exception->getMessage(), [], Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $method = $request->method();
            return response()->errorResponse("{$method} request method is not supported on this endpoint", [], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->errorResponse('The requested endpoint does not exist', [], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof HttpException) {
            return response()->errorResponse($exception->getStatusCode(), [], $exception->getMessage());
        }

        if ($exception instanceof APIResponseException) {
            return $exception->render();
        }

        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return response()->errorResponse('Session Expired', [], Response::HTTP_UNAUTHORIZED);

        }

        return response()->errorResponse('Something went wrong', [], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
