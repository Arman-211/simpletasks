<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * @param $request
     * @param Throwable $e
     * @return JsonResponse|Response
     */
    public function render($request, Throwable $e): JsonResponse|Response
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Resource not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof TooManyRequestsHttpException) {
            return response()->json([
                'message' => 'Too many requests'
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        return response()->json([
            'message' => 'Something went wrong'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
