<?php

namespace App\Infrastructure\Symfony\Controller;

use App\Application\Command\CreateUserCommand;
use App\Application\Command\LoginUserCommand;
use App\Application\Handler\CreateUserHandler;
use App\Application\Handler\LoginUserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    private CreateUserHandler $createUserHandler;
    private LoginUserHandler $loginUserHandler;

    public function __construct(CreateUserHandler $createUserHandler, LoginUserHandler $loginUserHandler)
    {
        $this->createUserHandler = $createUserHandler;
        $this->loginUserHandler = $loginUserHandler;
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $command = new CreateUserCommand(
            $data['email'],
            $data['password']
        );

        $this->createUserHandler->handle($command);

        return new JsonResponse(['status' => 'User created'], 201);
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $command = new LoginUserCommand(
            $data['email'],
            $data['password']
        );

        $token = $this->loginUserHandler->handle($command);

        return new JsonResponse(['token' => $token], 200);
    }
}
