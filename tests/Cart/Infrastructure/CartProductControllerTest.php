<?php

declare(strict_types=1);

namespace App\Tests\Cart\Infrastructure;

use App\Cart\Application\Read\GetCartProducts;
use App\Cart\Application\Update\RemoveProductFromCart;
use App\Cart\Application\Update\UpdateCartProduct;
use App\Cart\Domain\CartProduct;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Cart\Application\Update\ClearCart;
use App\Cart\Application\Read\GetCart;
use App\Cart\Application\Update\AddProductToCart;
use App\Cart\Domain\Entity\CartProduct as EntityCartProduct;
use App\Cart\Infrastructure\Controller\CartProductController;
use Tests\Cart\Domain\Entity\CartProductTest;

class CartProductControllerTest extends TestCase
{
    private $cartProductController;
    private AddProductToCart $addProductToCart;
    private RemoveProductFromCart $removeProductFromCart;
    private ClearCart $clearCart;
    private GetCart $getCart;
    private UpdateCartProduct $updateCartProduct;
    private GetCartProducts $getCartProducts;


    protected function setUp(): void
    {
        $this->addProductToCart = $this->createMock(AddProductToCart::class);
        $this->removeProductFromCart = $this->createMock(RemoveProductFromCart::class);
        $this->clearCart = $this->createMock(ClearCart::class);
        $this->getCart = $this->createMock(GetCart::class);
        $this->updateCartProduct = $this->createMock(UpdateCartProduct::class);
        $this->getCartProducts = $this->createMock(GetCartProducts::class);

        $this->cartProductController = new CartProductController(
            $this->addProductToCart,
            $this->removeProductFromCart,
            $this->clearCart,
            $this->getCart,
            $this->updateCartProduct,
            $this->getCartProducts
        );
    }

    public function testGetCartProducts(): void
    {
        $cartId = 1;
        $cartProducts = [
            [
                'cartId' => 1,
                'productId' => 2,
                'quantity' => 3,
                'name' => 'Product Name',
                'price' => 10.0,
            ]
        ];

        $this->getCartProducts
            ->expects($this->once())
            ->method('execute')
            ->with($cartId)
            ->willReturn($cartProducts);

        $response = $this->cartProductController->getCartProducts($cartId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($cartProducts), $response->getContent());
    }

    public function testAddProduct(): void
    {
        $data = [
            'cartId' => 1,
            'productId' => 2,
            'quantity' => 3,
            'name' => 'Product Name',
            'price' => 10.0
        ];
        $request = new Request([], [], [], [], [], [], json_encode($data));

        $cartProduct = EntityCartProduct::load(
            IntValueObject::fromInt($data['cartId']),
            IntValueObject::fromInt($data['productId']),
            IntValueObject::fromInt($data['quantity']),
            StringValueObject::fromString($data['name']),
            FloatValueObject::from($data['price'])
        );

        $this->addProductToCart
            ->expects($this->once())
            ->method('execute')
            ->with($cartProduct);

        $response = $this->cartProductController->addProduct($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Product added to cart']), $response->getContent());
    }

    public function testAddProductThrowsException(): void
    {
        $data = [
            'cartId' => 1,
            'productId' => 2,
            'quantity' => 3,
            'name' => 'Product Name',
            'price' => 10.0
        ];
        $request = new Request([], [], [], [], [], [], json_encode($data));

        $cartProduct = EntityCartProduct::load(
            IntValueObject::fromInt($data['cartId']),
            IntValueObject::fromInt($data['productId']),
            IntValueObject::fromInt($data['quantity']),
            StringValueObject::fromString($data['name']),
            FloatValueObject::from($data['price'])
        );

        $this->addProductToCart
            ->expects($this->once())
            ->method('execute')
            ->with($cartProduct)
            ->will($this->throwException(new \Exception('Product out of stock')));

        $response = $this->cartProductController->addProduct($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Product out of stock']), $response->getContent());
    }

    public function testUpdateCartProduct(): void
    {
        $data = [
            'cartId' => 1,
            'quantity' => 3,
            'name' => 'Updated Product Name',
            'price' => 20.0
        ];
        $request = new Request([], [], [], [], [], [], json_encode($data));
        $productId = 2;

        $this->updateCartProduct
            ->expects($this->once())
            ->method('execute')
            ->with(
                IntValueObject::fromInt($data['cartId']),
                IntValueObject::fromInt($productId),
                IntValueObject::fromInt($data['quantity']),
                StringValueObject::fromString($data['name']),
                FloatValueObject::from($data['price'])
            );

        $response = $this->cartProductController->updateCartProduct($request, $productId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Product updated in cart']), $response->getContent());
    }

    public function testUpdateCartProductThrowsException(): void
    {
        $data = [
            'cartId' => 1,
            'quantity' => 3,
            'name' => 'Updated Product Name',
            'price' => 20.0
        ];
        $request = new Request([], [], [], [], [], [], json_encode($data));
        $productId = 2;

        $this->updateCartProduct
            ->expects($this->once())
            ->method('execute')
            ->with(
                IntValueObject::fromInt($data['cartId']),
                IntValueObject::fromInt($productId),
                IntValueObject::fromInt($data['quantity']),
                StringValueObject::fromString($data['name']),
                FloatValueObject::from($data['price'])
            )
            ->will($this->throwException(new \Exception('Product not update')));

        $response = $this->cartProductController->updateCartProduct($request, $productId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Product not update']), $response->getContent());
    }

    public function testRemoveProduct(): void
    {
        $cartId = 1;
        $productId = 2;

        $this->removeProductFromCart
            ->expects($this->once())
            ->method('execute')
            ->with(IntValueObject::fromInt($cartId), IntValueObject::fromInt($productId));

        $response = $this->cartProductController->removeProduct($cartId, $productId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Product removed from cart']), $response->getContent());
    }
}