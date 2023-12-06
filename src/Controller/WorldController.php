<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use App\Service\UserBuilderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WorldController extends AbstractController
{
    public function hello(UserManager $userManager): Response
    {
        $users = $userManager->findUsersByLogin('Ivan Ivanov');

        return $this->json(array_map(static fn(User $user) => $user->toArray(), $users));
    }
}
