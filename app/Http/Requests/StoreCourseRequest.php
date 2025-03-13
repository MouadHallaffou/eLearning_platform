<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCourseRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true; // Autoriser tous les utilisateurs pour l'instant
    }

    /**
     * Obtenez les règles de validation qui s'appliquent à la requête.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|min:3|max:255|string',
            'description' => 'required|min:10|string',
            'content'     => 'required|string',
            'video'       => 'nullable|url',
            'cover'       => 'nullable|url',
            'duration'    => 'nullable|numeric|min:0',
            'level'       => 'required|in:beginner,intermediate,advanced',
            'category_id' => 'required|exists:categories,id',
            'tag_ids'     => 'nullable|array',
            'tag_ids.*'   => 'required|string|exists:tags,id', 
        ];
    }



    /**
     * Gestion des erreurs de validation.
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data'    => $validator->errors()
        ]));
    }
}
