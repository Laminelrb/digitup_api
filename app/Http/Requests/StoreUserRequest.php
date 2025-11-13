<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\CreateUserDTO;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // la Policy gère les permissions
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:agent,admin'
        ];
    }

    public function toDTO(): CreateUserDTO
    {
        return new CreateUserDTO(
            name: $this->name,
            email: $this->email,
            password: $this->password,
            role: $this->role
        );
    }
}
