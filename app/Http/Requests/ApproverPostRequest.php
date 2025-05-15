<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproverPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        \Log::info('ApproverPostRequest rules called');

        return [
            'name'   => 'required|string|max:255|unique:App\Models\Approver,name'
        ];
    }
}
