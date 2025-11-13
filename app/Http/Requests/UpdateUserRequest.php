<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\UpdateUserDTO;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $this->route('id'),
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|in:agent,admin'
        ];
    }

    public function toDTO(): UpdateUserDTO
    {
        return new UpdateUserDTO(
            name: $this->name,
            email: $this->email,
            password: $this->password,
            role: $this->role
        );
    }
}
