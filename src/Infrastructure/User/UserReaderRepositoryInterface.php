<?php

namespace App\Infrastructure\User;

use App\Domain\User\User;
use App\Domain\Profesor\Profesor;

interface UserReaderRepositoryInterface
{
    public function getUserLogin(string $username, string $password): ?User;
    public function getQrUserLogin(int $decriptedToken): bool;
    public function getUserById(int $userId): ?Profesor;
    public function getCheckOutTime(int $userId): string;
}
