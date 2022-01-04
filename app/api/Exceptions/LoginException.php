<?php

namespace App\api\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginException extends Exception
{
    public $message;
    public $code;


    public function __construct($message, Exception $previous = null, $code = 422)
    {
        $this->message = $message;
        $this->code = $code;
        parent::__construct($message, $this->code, $previous);
    }

    public function report()
    {
        return false;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render(Request $request)
    {

        return response()->json(
            [
                'message' => $this->message,
                'errors' => [
                    'email' =>
                        [
                            'Логин или пароль некорректный.
                        Пожалуйста введите верный логин и пароль.'
                        ]
                ],
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

}

