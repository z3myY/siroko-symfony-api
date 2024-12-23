<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Controller;

use App\User\Application\Create\CreateUser;
use App\User\Application\Read\GetUsers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package App\User\Infrastructure\Controller
 */
#[Nelmio\Areas(['public'])]
#[OA\Tag('Users')]
class UserController extends AbstractController
{
    public function __construct(
        private CreateUser $createUser,
        private GetUsers $getUsers
        )
    {
    }

    #[Route('/users', name: 'user_list', methods: ['GET'])]
    public function users(): JsonResponse
    {
        $users = $this->getUsers->execute();
        return new JsonResponse($users, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/users', name: 'create_user', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'user_name', type: 'Paloma1234'),
                new OA\Property(property: 'email', type: 'palom3@email.com'),
                new OA\Property(property: 'password', type: '1jNAloja')
            ]
        )
    )]
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $this->createUser->execute($data['user_name'], $data['email'], $data['password']);
        return new JsonResponse(['message' => 'User created'], JsonResponse::HTTP_CREATED);
    }
}