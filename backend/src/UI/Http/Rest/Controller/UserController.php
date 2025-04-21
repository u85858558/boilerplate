<?php
declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Application\User\Command\RegisterUser\RegisterUserCommand;
use App\Application\User\Query\FindUserByEmail\FindUserByEmailQuery;
use App\Infrastructure\Shared\Bus\Command\CommandBus;
use App\Infrastructure\Shared\Bus\Query\QueryBus;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[Route('/api/users')]
final class UserController
{
    private CommandBus $commandBus;
    private QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    /**
     * @throws Throwable
     * @throws ExceptionInterface
     */
    #[Route('/register', name: 'api_user_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->commandBus->handle(new RegisterUserCommand(
                $data['email'],
                $data['password'],
                $data['roles'] ?? ['ROLE_USER']
            ));

            return new JsonResponse(['message' => 'User registered successfully'], Response::HTTP_CREATED);

        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @throws Throwable
     */
    #[Route('/{email}', name: 'api_user_get', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUser(string $email): JsonResponse
    {
        try {
            $user = $this->queryBus->ask(new FindUserByEmailQuery($email));

            return new JsonResponse([
                'id' => $user->id(),
                'email' => $user->email(),
                'roles' => $user->roles(),
                'created_at' => $user->createdAt(),
                'updated_at' => $user->updatedAt(),
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
