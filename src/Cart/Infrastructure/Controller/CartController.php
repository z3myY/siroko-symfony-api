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
use App\Cart\Application\Read\GetCart;
use App\Cart\Application\Create\AddCart;
use App\Cart\Application\Read\GetAllCarts;
use App\Cart\Application\Read\GetLastCart;
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
        private AddCart $addCart,
        private GetAllCarts $getAllCarts,
        private GetLastCart $getLastCart
    ) {
    }

    #[Route('/carts', name: 'get_carts', methods: ['GET'])]
    public function carts(): JsonResponse
    {
        $carts = $this->getAllCarts->execute();
        return new JsonResponse($carts, JsonResponse::HTTP_OK);
    }
    

    #[Route('/carts/last', name: 'get_last_cart', methods: ['GET'])]
    public function getLastCart(): JsonResponse
    {
        $cart = $this->getLastCart->execute();
        return new JsonResponse($cart, JsonResponse::HTTP_OK);
    }

    #[Route('/carts/{id}', name: 'cart', methods: ['GET'])]
    public function cart(int $id): JsonResponse
    {
        $cart = $this->getCart->execute($id);
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
        try {
            $this->addCart->execute($data['userId']);
        } catch (\Throwable $th) {
            return new JsonResponse(['message' => 'Cart not created'], JsonResponse::HTTP_BAD_REQUEST);
        }
        return new JsonResponse(['message' => 'New cart created'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/carts/{id}/products', name: 'clear_cart', methods: ['DELETE'])]
    public function clearCart(int $id): JsonResponse
    {
        $this->clearCart->execute($id);
        return new JsonResponse(['message' => 'Cart cleared'], JsonResponse::HTTP_OK);
    }

    #[Route('/carts/{id}/products/count', name: 'count_products', methods: ['GET'])]
    public function countProducts(int $id): JsonResponse
    {
        $cart = $this->getCart->execute($id);
        return new JsonResponse(['count' => $cart['totalProducts']], JsonResponse::HTTP_OK);
    }
}