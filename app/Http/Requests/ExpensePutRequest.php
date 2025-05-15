<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ExpensePutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
             'approver_id' => [
                'required',
                'exists:approvers,id',
                Rule::unique('approvals')->ignore($this->route('id'))
            ]
        ];
    }
}
