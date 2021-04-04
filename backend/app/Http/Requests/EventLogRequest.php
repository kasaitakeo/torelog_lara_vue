<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventLogRequest extends FormRequest
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
        return [
            //  種目ログの重量、回数、セット数は全て必須で数値である
            'weight' => 'required|numeric|max:300',
            'rep' => 'required|numeric|max:30',
            'set' => 'required|numeric|max:20'
        ];
    }
}
