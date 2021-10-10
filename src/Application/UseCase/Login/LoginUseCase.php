<?php

namespace App\Application\UseCase\Login;

use App\Domain\User\User;
use App\Infrastructure\User\UserReaderRepositoryInterface;
use App\Application\UseCase\Login\Exception\GetLoginUserException;

class LoginUseCase
{
    private UserReaderRepositoryInterface $userReaderRepository;

    public function __construct(UserReaderRepositoryInterface $userReaderRepository)
    {
        $this->userReaderRepository = $userReaderRepository;
    }

    public function execute(string $username, string $password): User
    {
        $encryptPassword = sha1($password);
        $user = $this->userReaderRepository->getUserLogin($username, $encryptPassword);

        if (empty($user)) {
            throw new GetLoginUserException();
        }

        return $user;
    }
}
