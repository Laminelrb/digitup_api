<?php

namespace App\DTOs;

class CreateUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $role
    ) {}
}
