<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePost extends FormRequest
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

    public function rules()
    {
        $id = $this->route('post')->id;
        return [
            'title' => [
                'required',
                Rule::unique('posts')->where('id', '<>', $id),
            ],
            'body' => 'required',
        ];
    }
}
