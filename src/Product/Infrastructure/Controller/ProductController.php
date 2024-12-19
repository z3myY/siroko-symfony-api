<?php 

declare(strict_types=1);

namespace App\Product\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Product\Infrastructure\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use OpenApi\Attributes as OA;

/**
 * Class ProductController
 * @package App\Product\Infrastructure\Controller
 */
#[Nelmio\Areas(['public'])]
#[OA\Tag('Products')]
class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    #[Route('/products', name: 'products', methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns the list of products',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/ProductResponse')
        )
    )]
    public function listProduct() : JsonResponse 
    {
        $products = $this->productRepository->list();
        return new JsonResponse($products, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/products/{id}', name: 'product', methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns the list of products',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/ProductResponse')
        )
    )]    
    public function findProduct(int $id) : JsonResponse 
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($product->serialize(), JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}