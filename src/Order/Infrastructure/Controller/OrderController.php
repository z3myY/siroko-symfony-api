<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Controller;

use App\Order\Application\Create\CreateOrder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OrderController
 * @package App\Order\Infrastructure\Controller
 */
#[Nelmio\Areas(['public'])]
#[OA\Tag('Orders')]
final class OrderController extends AbstractController
{
    public function __construct(
        private CreateOrder $createOrder,
    )
    {
    }

    #[Route('/orders', name: 'create_order', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'customerId', type: 'integer'),
                new OA\Property(property: 'cartId', type: 'integer')
            ]
        )
    )]
    public function createOrder(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $customerId = $data['customerId'];
        $cartId = $data['cartId'];

        if (!$customerId || !$cartId || !is_int($customerId) || !is_int($cartId)) {
            return new JsonResponse(['message' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->createOrder->execute($customerId, $cartId);

        return new JsonResponse(['message' => 'Order created'], JsonResponse::HTTP_CREATED);
    }
}