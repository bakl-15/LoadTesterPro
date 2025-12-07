<?php
// src/Auth/Presentation/VerifyOtpController.php

namespace App\Auth\Presentation;

use Symfony\Component\HttpFoundation\Request;
use App\Auth\Application\Command\VerifyOtpCommand;
use App\Auth\Application\Handler\VerifyOtpHandler;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Auth\Domain\User\Exception\OtpInvalidException;
use App\Auth\Domain\User\Exception\UserNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class VerifyOtpController extends AbstractController
{
    public function __construct(private VerifyOtpHandler $handler) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $command = new VerifyOtpCommand(
                userId: $data['userId'],
                otp: (int) $data['otp']
            );

            $this->handler->handle($command);

            return new JsonResponse([
                'status' => 'success',
                'message' => 'OTP verified, user account is now active.'
            ], 200);

        } catch (UserNotFoundException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 404);

        } catch (OtpInvalidException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);

        } catch (\DomainException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);

        } catch (\Throwable $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Unexpected error',
            ], 500);
        }
    }
}
