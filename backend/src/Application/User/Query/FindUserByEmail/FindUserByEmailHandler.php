<?php

declare(strict_types=1);

namespace App\Application\User\Query\FindUserByEmail;

use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\Shared\Bus\Query\QueryHandlerInterface;

final class FindUserByEmailHandler implements QueryHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(FindUserByEmailQuery $query): UserResponse
    {
        $email = Email::fromString($query->email());
        $user = $this->userRepository->findByEmail($email);

        if (null === $user) {
            throw new UserNotFoundException(sprintf('User with email %s not found', $email));
        }

        return UserResponse::fromUser($user);
    }
}
