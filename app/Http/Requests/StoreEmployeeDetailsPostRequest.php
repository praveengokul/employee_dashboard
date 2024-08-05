<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreEmployeeDetailsPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|string|unique:employees,employee_id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees,email',
            'dob' => 'required|date',
            'doj' => 'required|date',
        ];
    }
    public function messages()
    {
        return [
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $errorMessage = $errors->first();

        throw new HttpResponseException(response()->json([
            'status_code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            'message' => $errorMessage,
            'errors' => $errors,
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
