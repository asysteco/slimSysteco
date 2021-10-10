<?php

namespace App\Infrastructure\User;

use App\Domain\User\User;

interface UserReaderRepositoryInterface
{
    public function getUserLogin(string $username, string $password): ?User;
    public function getQrUserLogin(int $decriptedToken): bool;
}
