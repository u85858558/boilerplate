<?php

declare(strict_types=1);

namespace App\Application\User\Command\RegisterUser;

use App\Domain\Shared\IdGenerator;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\HashedPassword;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\Shared\Bus\Command\CommandHandlerInterface;

final class RegisterUserHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private IdGenerator $idGenerator;

    public function __construct(UserRepositoryInterface $userRepository, IdGenerator $idGenerator)
    {
        $this->userRepository = $userRepository;
        $this->idGenerator = $idGenerator;
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        $userId = UserId::generate($this->idGenerator);
        $email = Email::fromString($command->email());
        $password = HashedPassword::encode($command->plainPassword());

        $user = User::create(
            $userId,
            $email,
            $password,
            $command->roles()
        );

        $this->userRepository->save($user);
    }
}
