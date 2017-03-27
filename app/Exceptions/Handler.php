<?php

namespace App\Exceptions;

use Exception;
use Auth;
use Redirect;
use Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }
        
        if ($e instanceof Tymon\JWTAuth\Exceptions\TokenExpiredException) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } else if ($e instanceof Tymon\JWTAuth\Exceptions\TokenInvalidException) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }

        ///////////////////////////////////CUSTOM ERROR//////////////////////////////////////////////
        if($e instanceof NotFoundHttpException)
        {
            if (Auth::user()) {
               return response()->view('error.error', [], 404);
            }else{
                return response()->view('error.error', [], 404);
            }
        }

        if ($e instanceof \Illuminate\Session\TokenMismatchException){
            
            /*return response()->view('error.error500', [], 500);*/
            Session::flash("warning","Failed! Token expired!");
            return Redirect::back();
        }
        ////////////////////////////////////////////////////////////////////////////////

        return parent::render($request, $e);
    }
}
