<?php

namespace App\Application\Handler;

use App\Application\Command\LoginUserCommand;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginUserHandler
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private JWTTokenManagerInterface $jwtTokenManager;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtTokenManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->jwtTokenManager = $jwtTokenManager;
    }

    public function handle(LoginUserCommand $command): string
    {
        $user = $this->userRepository->findOneByEmail($command->email);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $command->password)) {
            throw new BadCredentialsException();
        }

        return $this->jwtTokenManager->create($user);
    }
}
