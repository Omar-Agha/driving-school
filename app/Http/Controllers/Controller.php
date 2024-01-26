<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    /**
     * Send a success response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendSuccess($data, string $message = null, int $code = Response::HTTP_OK)
    {

        return response()->json([
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Send an error response.
     *
     * @param string|null $message
     * @param int $code
     * @param array|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendError(string $message = null, int $code = Response::HTTP_BAD_REQUEST, array $errors = null)
    {
        $response = [
            'success' => false,
            'code' => $code,
            'message' => $message,
            'data'=>[]
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }


    public function test()
    {
        return $this->sendError("Error my");

        return $this->sendSuccess(User::all(), "data", Response::HTTP_BAD_GATEWAY);
    }
}
