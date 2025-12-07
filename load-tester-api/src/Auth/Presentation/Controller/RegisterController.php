
<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Auth\Application\Handler\RegisterUserHandler;
use App\Auth\Application\User\Command\RegisterUserCommand;
use App\Auth\Domain\User\Exception\EmailAlreadyExistsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RegisterController extends AbstractController
{
    public function __construct(private RegisterUserHandler $handler) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $command = new RegisterUserCommand(
                email: $data['email'],
                password: $data['password']
            );

            $userId = $this->handler->handle($command);

            return new JsonResponse([
                'status' => 'success',
                'userId' => $userId,
            ], 201);

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

