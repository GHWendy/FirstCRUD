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
    //TODO: Ask what should i change, this level or the ProductController one.

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
                'data.attributes.name' => 'bail|required',
                'data.attributes.price' => 'required|gt:0|numeric'
                ];
                break;   
            case 'PUT': 
                $rules = [
                'data.attributes.price' => 'gt:0|numeric'
               ];
                break;         
            default:
                break;
        }
        return $rules;
    }

    protected function failedValidation(Validator $validator) {

        $errors = [
                'code' => 'ERROR-1',
                'title' => 'Unprocessable Entity'
                ];
        $response = [ 'errors' => $errors]; 
        throw new HttpResponseException(response()->json($response, 422)); 
    
    }
}
