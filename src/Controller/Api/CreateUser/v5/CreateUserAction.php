<?php

namespace App\Controller\Api\CreateUser\v5;

use App\Controller\Api\CreateUser\v5\Input\CreateUserDTO;
use App\Controller\Common\ErrorResponseTrait;
use App\Domain\Command\CreateUser\CreateUserCommand;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Controller\Api\CreateUser\v5\Output\UserIsCreatedDTO;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateUserAction extends AbstractFOSRestController
{
    use ErrorResponseTrait;

    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    #[OA\Post(
        operationId: 'addUser',
        requestBody: new OA\RequestBody(
            description: 'Input data format',
            content: new Model(type: CreateUserDTO::class),
        ),
        tags: ['Пользователи'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new Model(type: UserIsCreatedDTO::class),
            )
        ]
    )]
    #[Rest\Post(path: '/api/v5/users')]
    public function saveUserAction(#[MapRequestPayload] CreateUserDTO $request): Response
    {
        $this->messageBus->dispatch(CreateUserCommand::createFromRequest($request));

        return $this->handleView($this->view(['success' => true]));
    }
}
