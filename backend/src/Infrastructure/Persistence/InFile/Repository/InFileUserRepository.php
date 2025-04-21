<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\InFile\Repository;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\Bridge\InFile\FilesystemHandler;

final class InFileUserRepository implements UserRepositoryInterface
{
    private const USERS_FILE = 'users.json';
    private FilesystemHandler $filesystemHandler;

    public function __construct(FilesystemHandler $filesystemHandler)
    {
        $this->filesystemHandler = $filesystemHandler;
    }

    public function save(User $user): void
    {
        $users = $this->findAll();
        $users[$user->id()->toString()] = $this->serialize($user);
        
        $this->filesystemHandler->createFile(self::USERS_FILE, json_encode($users));
    }

    public function findById(UserId $id): ?User
    {
        $users = $this->findAll();
        
        if (!isset($users[$id->toString()])) {
            return null;
        }
        
        return $this->deserialize($users[$id->toString()]);
    }

    public function findByEmail(Email $email): ?User
    {
        $users = $this->findAll();
        
        foreach ($users as $userData) {
            if ($userData['email'] === $email->value()) {
                return $this->deserialize($userData);
            }
        }
        
        return null;
    }

    public function findAll(): array
    {
        $content = $this->filesystemHandler->readFile(self::USERS_FILE);
        
        if (null === $content) {
            return [];
        }
        
        return json_decode($content, true) ?? [];
    }

    public function remove(User $user): void
    {
        $users = $this->findAll();
        
        if (isset($users[$user->id()->toString()])) {
            unset($users[$user->id()->toString()]);
            $this->filesystemHandler->createFile(self::USERS_FILE, json_encode($users));
        }
    }

    private function serialize(User $user): array
    {
        return [
            'id' => $user->id()->toString(),
            'email' => $user->email()->value(),
            'password' => $user->password()->toString(),
            'roles' => $user->roles(),
            'created_at' => $user->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $user->updatedAt() ? $user->updatedAt()->format('Y-m-d H:i:s') : null,
        ];
    }

    private function deserialize(array $data): User
    {
        return User::fromPrimitives(
            $data['id'],
            $data['email'],
            $data['password'],
            $data['roles'],
            $data['created_at'],
            $data['updated_at']
        );
    }
}
