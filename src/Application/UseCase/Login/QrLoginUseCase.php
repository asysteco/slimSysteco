<?php

namespace App\Application\UseCase\Login;

use App\Infrastructure\User\UserReaderRepositoryInterface;
use App\Application\UseCase\Login\Exception\GetReaderLoginException;
use App\Application\UseCase\Login\Exception\InvalidLoginTokenException;
use App\Domain\Sites\SiteOptions;
use App\Infrastructure\Utility\MyCrypt;

class QrLoginUseCase
{
    private UserReaderRepositoryInterface $userReaderRepository;

    public function __construct(UserReaderRepositoryInterface $userReaderRepository)
    {
        $this->userReaderRepository = $userReaderRepository;
    }

    public function execute(string $token, SiteOptions $siteOptions): void
    {
        $decriptedToken = MyCrypt::decrypt($siteOptions->cryptKey(), $token, $siteOptions->dailyQr());
        if (!$decriptedToken) {
            throw new InvalidLoginTokenException();
        }

        $logged = $this->userReaderRepository->getQrUserLogin((int)$decriptedToken);

        if ($logged === false) {
            throw new GetReaderLoginException();
        }
    }
}
