<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class CategoryTypes extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id'  => 'required|exists:categories,id,status,1',
            'name'        => 'required|unique:category_types'
        ];
    }
    /**
     * @param array $errors
     *
     * @return JsonResponse
     */
    public function response(array $errors)
    {
        print_r('sdasd');exit;
        if (Request::ajax() || $this->wantsJson())
        {
            error($errors[0], 0);
        }

        return $errors;
    }

    protected function formatErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }



    public function messages()
    {
        return [
           
        ];
    }

    /**
     * Get the sanitized input for the request.
     *
     * @return array
     */
    public function sanitize()
    {
        return $this->all();
    }
}
