<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $login_user = auth()->user();

        return [
            //  種目ログの重量、回数、セット数は全て必須で数値である
            'name' => 'required|string|max:255',
            'screen_name' => ['required', 'string', 'max:30', Rule::unique('App\Models\User')->ignore($login_user->id)],
            'profile_text' => 'required|string|max:150',
            'profile_image' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('App\Models\User')->ignore($login_user->id)]
        ];
    }
}
