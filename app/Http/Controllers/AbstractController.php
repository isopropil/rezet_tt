<?php


namespace App\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;

abstract class AbstractController extends BaseController {

    const SUCCESS_STATUS = 'ok';
    const ERROR_STATUS = 'error';

    protected function successResponse($payload) {
        return \response()->json([
            'status' => static::SUCCESS_STATUS,
            'payload' => $payload
        ]);
    }

    protected function errorResponse($message) {
        return \response()->json([
            'status' => static::ERROR_STATUS,
            'payload' => $message
        ]);
    }

}
