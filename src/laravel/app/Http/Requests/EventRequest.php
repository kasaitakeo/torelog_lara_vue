<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            //  種目の部位、名、は必須
            'eventPart' => 'required|string|max:10',
            'eventName' => 'required|string|max:30',
            'eventExplanation' => 'required|string|max:200'
        ];
    }
}
