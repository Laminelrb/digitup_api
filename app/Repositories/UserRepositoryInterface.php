<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function allAgents();

    public function findOrFail(int $id): User;

    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): void;
}
