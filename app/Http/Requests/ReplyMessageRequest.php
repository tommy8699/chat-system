<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:jpg,png,pdf', 'max:10240'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->has('text') && !$this->hasFile('file')) {
                $validator->errors()->add('text', 'Odpoveď musí obsahovať text alebo súbor.');
            }

            if ($this->has('text') && $this->hasFile('file')) {
                $validator->errors()->add('text', 'Odpoveď nemôže obsahovať text aj súbor súčasne.');
            }
        });
    }
}
