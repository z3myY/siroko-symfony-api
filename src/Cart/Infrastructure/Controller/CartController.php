<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Controller;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }   

    #[Route('/cart', name: 'cart_index')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig');
    }

    #[Route('/product', name: 'product_list')]
    public function listProduct(): JsonResponse
    {
        // $products = [
        //     [
        //         'id' => 1,
        //         'name' => 'Product 1',
        //         'price' => 1000,
        //         'stock' => 10,
        //         'category' => 'Category 1',
        //         'sku' => 'SKU1',
        //     ],
        //     [
        //         'id' => 2,
        //         'name' => 'Product 2',
        //         'price' => 2000,
        //         'stock' => 20,
        //         'category' => 'Category 2',
        //         'sku' => 'SKU2',
        //     ],
        //     [
        //         'id' => 3,
        //         'name' => 'Product 3',
        //         'price' => 3000,
        //         'stock' => 30,
        //         'category' => 'Category 3',
        //         'sku' => 'SKU3',
        //     ],
        // ];

        try {
            $products = $this->productRepository->list();
            dd($products);
            return new JsonResponse($products, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
    
            // Return a JSON response with the error message
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}