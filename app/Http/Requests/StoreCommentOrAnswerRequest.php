<?php

namespace App\Http\Requests;

use App\Rules\ImagesOrFiles;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentOrAnswerRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|regex:/^[a-zA-Z0-9]+$/',
            'gender' => 'required',
            'email' => 'required|email:rfc,dns',
            'homepage' => 'url',
            'text' => 'required',
            'file' => new ImagesOrFiles(),
            'recaptcha_token' => 'required'
        ];
    }
}
