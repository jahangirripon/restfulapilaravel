<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\QueryException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    use ApiResponser;


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
        if($exception instanceof ValidationException)
        {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if($exception instanceof AuthenticationException)
        {
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof AuthorizationException)
        {
            return $this->errorResponse($exception->getMessage(), 403);
        }

        if($exception instanceof ModelNotFoundException)
        {
            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does not match with any  $modelName  with the specified identifier.", 404);
        }

        if($exception instanceof MethodNotAllowedHttpException)
        {
            return $this->errorResponse('The specified method not allowed!', 405 );
        }

        if($exception instanceof NotFoundHttpException)
        {
            return $this->errorResponse('The specified url cannot be found!', 404 );
        }

        if($exception instanceof HttpException)
        {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode() );
        }

        if($exception instanceof QueryException)
        {
            $errorCode = $exception->errorInfo[1];

            if($errorCode == 1451)
            {
                return $this->errorResponse('Cannot remove this resource permanently. It is related with any other resource.', 409);
            }
           // dd($exception);
        }

        if($exception instanceof TokenMismatchException)
        {
            return redirect()->back()->withInput($request->input());
        }

        if($exception instanceof TokenMismatchException)
        {
            return redirect()->back()->withInput($request->input());
        }

        if(config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Unexpected Exception, Try later', 500);


    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($this->isFrontend($request)) {
            return redirect()->guest('login');
        }

        return $this->errorResponse('Unauthenticated.', 401);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        if($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($error, 422) : redirect()
            ->back()
            ->withInput($request->input())
            ->withErrors($errors); 
        }

        return response()->json($errors, 422);
    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
