<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponser;
use App\Traits\FileUploader;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    use ApiResponser, FileUploader;
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
    public function render($request, Throwable $e)
    {
        $code = $e->getCode();
        $msg  = $e->getMessage();

        if (isset(request()->transaction) && $request->transaction) {
            DB::rollback();
        }
        if (isset(request()->imageUrl)) {
            $this->deleteFile(request()->imageUrl);
        }
        if (!$code || $code > 599 || $code <= 0 || gettype($code) !== "integer") {
            $code = 500;
        }
        if ($e instanceof NotFoundHttpException) {
            $code = 404;
            $msg = 'Route not found';
        }
        if ($e instanceof ValidationException) {
            $msg = $e->validator->errors()->all();
            $code = 400;
        }
        return response()->json([
            'status' => 'Error',
            'message' => $msg,
            'model' => null,
            'statusCode' => $code
        ], $code);
    }
}
