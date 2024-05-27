<?php

namespace App\Application\Handler;

use App\Application\Query\UserQuery;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;

class UserQueryHandler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(UserQuery $query): ?User
    {
        return $this->userRepository->findOneByEmail($query->getEmail());
    }
}