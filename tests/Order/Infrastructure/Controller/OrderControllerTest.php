<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Controller;

use App\Order\Application\Create\CreateOrder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class OrderControllerTest extends TestCase
{
    private CreateOrder $createOrder;
    private $controller;

    protected function setUp(): void
    {
        $this->createOrder = $this->createMock(CreateOrder::class);
        $this->controller = new OrderController($this->createOrder);
    }

    public function testCreateOrderReturnsJsonResponse(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn(json_encode([
            'customerId' => 1,
            'cartId' => 2
        ]));

        $this->createOrder
            ->expects($this->once())
            ->method('execute')
            ->with(1, 2);

        $response = $this->controller->createOrder($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(['message' => 'Order created'], json_decode($response->getContent(), true));
    }

    public function testCreateOrderWithInvalidData(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn(json_encode([
            'customerId' => null,
            'cartId' => null
        ]));

        $this->createOrder
            ->expects($this->never())
            ->method('execute');

        $response = $this->controller->createOrder($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals(['message' => 'Invalid data'], json_decode($response->getContent(), true));
    }
}