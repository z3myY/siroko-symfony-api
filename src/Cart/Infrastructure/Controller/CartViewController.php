<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartViewController
 * @package App\Cart\Infrastructure\Controller
 */
class CartViewController extends AbstractController
{
    #[Route('/index', name: 'cart_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig');
    }
}