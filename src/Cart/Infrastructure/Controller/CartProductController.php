<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Cart\Application\Update\AddProductToCart;
use App\Cart\Application\Update\RemoveProductFromCart;
use App\Cart\Application\Update\ClearCart;
use App\Cart\Application\Update\GetCart;
use App\Cart\Application\Update\UpdateCartProduct;
use App\Cart\Domain\Entity\CartProduct;
use App\Shared\Domain\ValueObject\IntValueObject;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use OpenApi\Attributes as OA;

/**
 * Class CartProductController
 * @package App\Cart\Infrastructure\Controller
 */
#[Nelmio\Areas(['public'])]
#[OA\Tag('Carts')]
class CartProductController extends AbstractController
{
    public function __construct(
        private AddProductToCart $addProductToCart,
        private RemoveProductFromCart $removeProductFromCart,
        private ClearCart $clearCart,
        private GetCart $getCart,
        private UpdateCartProduct $updateCartProduct
    ) {
    }

    #[Route('/carts/products', name: 'add_product_to_cart', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'cartId', type: 'integer'),
                new OA\Property(property: 'productId', type: 'integer'),
                new OA\Property(property: 'quantity', type: 'integer')
            ]
        )
    )]
    public function addProduct(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cartId = IntValueObject::fromInt($data['cartId']);
        $productId = IntValueObject::fromInt($data['productId']);
        $quantity = IntValueObject::fromInt($data['quantity']);

        $cartProduct = new CartProduct($cartId, $productId, $quantity);
        $this->addProductToCart->execute($cartProduct);

        return new JsonResponse([
            'message' => 'Product added to cart'
        ], JsonResponse::HTTP_CREATED);
    }

    #[Route('/carts/products/{id}', name: 'update_cart_product', methods: ['PUT'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'cartId', type: 'integer'),
                new OA\Property(property: 'quantity', type: 'integer')
            ]
        )
    )]
    public function updateCartProduct(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $productId = IntValueObject::fromInt($id);
        $cartId = IntValueObject::fromInt($data['cartId']);
        $quantity = IntValueObject::fromInt($data['quantity']);

        $this->updateCartProduct->execute($cartId, $productId, $quantity);

        return new JsonResponse(['message' => 'Product updated in cart'], JsonResponse::HTTP_OK);
    }

    #[Route('/carts/{cartId}/products/{productId}', name: 'remove_product_from_cart', methods: ['DELETE'])]
    public function removeProduct(int $cartId, int $productId): JsonResponse
    {
        $cartId = IntValueObject::fromInt($cartId);
        $productId = IntValueObject::fromInt($productId);
        $this->removeProductFromCart->execute($cartId ,$productId);

        return new JsonResponse(['message' => 'Product removed from cart'], JsonResponse::HTTP_OK);
    }
}