<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuListStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   
        if(request()->isMethod('post')){
            return [
                'menu_name' => 'required|string|max:258',
                'menu_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ];
        } else {
            return [
                'menu_name' => 'required|string|max:258',
                'menu_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ];
        }
    }

    /**
     *  Custom message for validation
     * 
     * @return array
     */
    public function messages() {
        if(request()->isMethod('post')){
            return [
                'menu_name.required' => 'Menu name is required',
                'menu_image.required' => 'Image is required'
            ];
        } else {
            return [
                'menu_name.required' => 'Menu name is required'
            ];
        }
    }
}
