<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Cart\Application\Update\AddProductToCart;
use App\Cart\Application\Update\RemoveProductFromCart;
use App\Cart\Application\Update\ClearCart;
use App\Cart\Application\Update\GetCart;
use App\Cart\Application\Create\AddCart;
use App\Shared\Domain\ValueObject\IntValueObject;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use OpenApi\Attributes as OA;

/**
 * Class CartController
 * @package App\Cart\Infrastructure\Controller
 */
#[Nelmio\Areas(['public'])]
#[OA\Tag('Carts')]
class CartController extends AbstractController
{
    public function __construct(
        private AddProductToCart $addProductToCart,
        private RemoveProductFromCart $removeProductFromCart,
        private ClearCart $clearCart,
        private GetCart $getCart,
        private AddCart $addCart
    ) {
    }

    #[Route('/carts/{id}', name: 'cart', methods: ['GET'])]
    public function cart(int $id): JsonResponse
    {
        $cart = $this->getCart->execute(IntValueObject::fromInt($id));
        return new JsonResponse($cart, JsonResponse::HTTP_OK);
    }

    #[Route('/carts', name: 'new_cart', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'userId', type: 'integer')
            ]
        )
    )]
    public function newCart(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cartCreated = $this->addCart->execute($data['userId']);

        if (!$cartCreated) {
            return new JsonResponse(['message' => 'Cart not created'], JsonResponse::HTTP_BAD_REQUEST);
        }
        return new JsonResponse(['message' => 'New cart created'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/carts/clear', name: 'clear_cart', methods: ['POST'])]
    public function clearCart(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $this->clearCart->execute($data['cartId']);
        return new JsonResponse(['status' => 'Cart cleared'], JsonResponse::HTTP_OK);
    }
}