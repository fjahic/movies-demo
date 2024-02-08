<?php

namespace App\Http\Requests;

use App\Models\Review;
use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'movie_id' => ['required', 'int', 'exists:movies,id'],
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
            'rating' => ['required', 'int', 'between:1,10'],
        ];
    }
}
