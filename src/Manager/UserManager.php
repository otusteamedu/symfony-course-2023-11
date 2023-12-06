<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function create(string $login): User
    {
        $user = new User();
        $user->setLogin($login);
        $user->setCreatedAt();
        $user->setUpdatedAt();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function clearEntityManager(): void
    {
        $this->entityManager->clear();
    }

    public function findUser(int $id): ?User
    {
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->find($id);

        return $user instanceof User ? $user : null;
    }

    public function subscribeUser(User $author, User $follower): void
    {
        $author->addFollower($follower);
        $follower->addAuthor($author);
        $this->entityManager->flush();
    }

    /**
     * @return User[]
     */
    public function findUsersByLogin(string $name): array
    {
        return $this->entityManager->getRepository(User::class)->findBy(['login' => $name]);
    }

    /**
     * @return User[]
     */
    public function getUserList(): array
    {
        return [
            new User('Иван', 'Сергеевич', 'Сапогов', '+71112223344'),
            new User('Фёдор', 'Викторович', 'Лаптев', '+72223334455'),
            new User('Пётр', 'Михайлович', 'Стеклов', '+73334445566'),
            new User('Игнат', 'Глебович', 'Лопухов', '+74445556677'),
        ];
    }

    public function getUsersListVue(): array
    {
        return array_map(
            static fn(User $user) => $user->toArray(),
            $this->getUserList()
        );
    }
}
