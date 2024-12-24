<?php

namespace Tests\Cart\Infrastructure\Controller;

use App\Cart\Application\Update\AddProductToCart;
use App\Cart\Application\Update\RemoveProductFromCart;
use App\Cart\Application\Update\ClearCart;
use App\Cart\Application\Read\GetCart;
use App\Cart\Application\Create\AddCart;
use App\Cart\Application\Read\GetAllCarts;
use App\Cart\Application\Read\GetLastCart;
use App\Cart\Infrastructure\Controller\CartController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CartControllerTest extends TestCase
{
    private AddProductToCart $addProductToCart;
    private RemoveProductFromCart $removeProductFromCart;
    private ClearCart $clearCart;
    private GetCart $getCart;
    private AddCart $addCart;
    private GetAllCarts $getAllCarts;
    private GetLastCart $getLastCart;
    private CartController $cartController;


    protected function setUp(): void
    {
        $this->addProductToCart = $this->createMock(AddProductToCart::class);
        $this->removeProductFromCart = $this->createMock(RemoveProductFromCart::class);
        $this->clearCart = $this->createMock(ClearCart::class);
        $this->getCart = $this->createMock(GetCart::class);
        $this->addCart = $this->createMock(AddCart::class);
        $this->getAllCarts = $this->createMock(GetAllCarts::class);
        $this->getLastCart = $this->createMock(GetLastCart::class);

        $this->cartController = new CartController(
            $this->addProductToCart,
            $this->removeProductFromCart,
            $this->clearCart,
            $this->getCart,
            $this->addCart,
            $this->getAllCarts,
            $this->getLastCart
        );
    }

    public function testCarts(): void
    {
        $carts = [['id' => 1, 'userId' => 1, 'products' => [], 'totalProducts' => 0, 'totalPrice' => 0.0]];

        $this->getAllCarts
            ->expects($this->once())
            ->method('execute')
            ->willReturn($carts);

        $response = $this->cartController->carts();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($carts), $response->getContent());
    }

    public function testGetLastCart(): void
    {
        $cart = ['id' => 1, 'userId' => 1, 'products' => [], 'totalProducts' => 0, 'totalPrice' => 0.0];

        $this->getLastCart
            ->expects($this->once())
            ->method('execute')
            ->willReturn($cart);

        $response = $this->cartController->getLastCart();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($cart), $response->getContent());
    }

    public function testCart(): void
    {
        $cart = ['id' => 1, 'userId' => 1, 'products' => [], 'totalProducts' => 0, 'totalPrice' => 0.0];

        $this->getCart
            ->expects($this->once())
            ->method('execute')
            ->with(1)
            ->willReturn($cart);

        $response = $this->cartController->cart(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($cart), $response->getContent());
    }

    public function testNewCart(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode(['userId' => 1]));

        $this->addCart
            ->expects($this->once())
            ->method('execute')
            ->with(1);

        $response = $this->cartController->newCart($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'New cart created']), $response->getContent());
    }

    public function testNewCartThrowsException(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode(['userId' => 1]));

        $this->addCart
            ->expects($this->once())
            ->method('execute')
            ->with(1)
            ->will($this->throwException(new \Exception('Cart not created')));

        $response = $this->cartController->newCart($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Cart not created']), $response->getContent());
    }

    public function testClearCart(): void
    {
        $this->clearCart
            ->expects($this->once())
            ->method('execute')
            ->with(1);

        $response = $this->cartController->clearCart(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Cart cleared']), $response->getContent());
    }

    public function testCountProducts(): void
    {
        $cart = ['id' => 1, 'userId' => 1, 'products' => [], 'totalProducts' => 5, 'totalPrice' => 0.0];

        $this->getCart
            ->expects($this->once())
            ->method('execute')
            ->with(1)
            ->willReturn($cart);

        $response = $this->cartController->countProducts(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode(['count' => 5]), $response->getContent());
    }
}