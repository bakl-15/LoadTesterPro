<?php

namespace App\Auth\Presentation\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Auth\Application\User\Command\LoginUserCommand;
use App\Auth\Application\User\CommandHandlers\LoginUserHandler;

final class LoginController extends AbstractController
{
    public function __construct(private LoginUserHandler $handler) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $command = new LoginUserCommand(
                email: $data['email'],
                password: $data['password']
            );

            $jwt = $this->handler->handle($command);

            return new JsonResponse([
                'status' => 'success',
                'token' => $jwt
            ]);
        } catch (\DomainException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }
    }
}
