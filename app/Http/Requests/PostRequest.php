<?php

namespace App\Http\Requests;

use Auth;
use App\Http\Requests\Request;

class PostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'title' => 'required|min:5|max:80',
        'content' => 'required|min:10|max:1000'
        ];
    }
}
