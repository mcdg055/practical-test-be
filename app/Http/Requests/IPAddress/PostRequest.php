<?php

namespace App\Http\Requests\IPAddress;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ip' => 'required|ip|unique:ip_addresses,ip',
            'label' => 'required|string',
            'type' => 'required|in:IPv4,IPv6',
            'comment' => 'string|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'ip.unique' => 'The IP address already exists.',
        ];
    }
}
