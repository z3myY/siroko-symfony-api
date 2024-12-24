<?php 

declare(strict_types=1);

namespace App\Product\Infrastructure\Controller;

use App\Product\Application\Read\FindProduct;
use App\Product\Application\Read\ListProducts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
    public function __construct(private FindProduct $findProduct, private ListProducts $listProducts)
    {
    }

    #[Route('/products', name: 'products', methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns list of products',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/ProductsResponse')
        )
    )]
    public function listProduct() : JsonResponse 
    {
        $products = $this->listProducts->execute();
        return new JsonResponse($products, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/products/{id}', name: 'product', methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns product',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/ProductResponse')
        )
    )]    
    public function findProduct(int $id) : JsonResponse 
    {
        $product = $this->findProduct->execute($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($product, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}