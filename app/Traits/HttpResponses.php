<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

trait HttpResponses
{
    protected function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'status_code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
    protected function error($data, $message = null, $code = 422)
    {
        return response()->json([
            'status_code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
    protected function validationError($validator)
    {
        $errors = $validator->errors();
        $errorMessage = $errors->first();

        throw new HttpResponseException(response()->json([
            'status_code' => JsonResponse::HTTP_BAD_REQUEST,
            'message' => $errorMessage,
            'errors' => $errors,
        ], JsonResponse::HTTP_BAD_REQUEST));

        // $firstErrorProperty = null;
        // foreach ($errors as $property => $value) {
        //     $firstErrorProperty = $property;
        //     break;
        // }

        // return $this->error([], $errors->first($firstErrorProperty), 400);
    }
}
