<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['success' => false, 'error' => 'The requested resource could not be found'], 404);
        }

        if ($exception instanceof \DomainException) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 400);
        }

        if ($exception instanceof \Exception) {
            return response()->json(['success' => false, 'error' => 'Oops! Something went wrong', 'data' => [
                            'error' => $exception->getMessage(),
                            'trace' => $exception->getTrace()]
            ], 500);
        }
        return parent::render($request, $exception);
    }

}
