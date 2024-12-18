<?php

namespace App\Item\Infrastructure\Controller;

use App\Item\Infrastructure\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use OpenApi\Attributes as OA;

/**
 * Class ItemController
 * @package App\Item\Infrastructure\Controller
 */
#[Nelmio\Areas(['public'])]
#[OA\Tag('Items')]
class ItemController extends AbstractController
{
    public function __construct(private ItemRepository $itemRepository)
    {
    }

    #[Route('/items', name: 'item_list', methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns the list of items',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/ItemResponse')
        )
    )]
    public function listItems(): JsonResponse
    {
        $items = $this->itemRepository->findAll();
        return new JsonResponse($items, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/items/{id}', name: 'item_details', methods: ['GET'])]
    #[OA\PathParameter(name: 'id', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns the details of an item',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'name', type: 'string', example: 'Item name')
            ]
        )
    )]
    public function getItem(int $id): JsonResponse
    {
        $item = $this->itemRepository->findById($id);
        if (!$item) {
            return new JsonResponse(['error' => 'Item not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        return new JsonResponse($item->serialize(), JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/items/one/{id}', name: 'find_one_item', methods: ['GET'])]
    #[OA\PathParameter(name: 'id', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns the details of one item',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 2),
                new OA\Property(property: 'name', type: 'string', example: 'Item name')
            ]
        )
    )]
    public function findOne(int $id): JsonResponse
    {
        $item = $this->itemRepository->find($id);
        if (!$item) {
            return new JsonResponse(['error' => 'Item not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        return new JsonResponse($item->serialize(), JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}