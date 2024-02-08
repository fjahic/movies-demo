<?php

namespace App\Http\Requests;

use App\Models\Review;
use Illuminate\Foundation\Http\FormRequest;

class ReviewUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required_without_all:body,rating', 'string'],
            'body' => ['required_without_all:title,rating', 'string'],
            'rating' => ['required_without_all:title,body', 'int', 'between:1,10'],
        ];
    }
}
