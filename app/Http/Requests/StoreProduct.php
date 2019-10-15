<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
 use Illuminate\Http\Exceptions\HttpResponseException;
    use Illuminate\Contracts\Validation\Validator;

class StoreProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        switch ($this-> method()) {
            case 'POST':
            $rules = [
                'name' => 'bail|required',
                'price' => 'required|gt:0|numeric',
                ];
                break;   
            case 'PUT': 
                $rules = [
                'price' => 'gt:0|numeric'
                ];
                break;         
            default:
                break;
        }
        return $rules;
    }
}
