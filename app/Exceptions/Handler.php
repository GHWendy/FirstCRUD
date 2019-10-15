<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;


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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
    
        return parent::render($request, $exception);
    }

protected function invalidJson($request, ValidationException $exception)
{
    $response = ['errors' => []];

    switch ($exception -> status) {
    case '422':
        $response['errors'] = [
                                'code' => 'ERROR-1',
                                'title' => 'Unprocessable Entity'
                                ];
        break;
     /*case '404':
        $response['errors'] = [
                                'code' => 'ERROR-2',
                                'title' => 'Not Found'
                                ];
        break;   */
    
    default:
        $response = [
            'code' => 'ERROR-2',
                                'title' => 'Not Found'
        ];
        break;
}
    return response()->json($response, $exception->status);
}

}
