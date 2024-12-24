<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CartService extends AbstractController
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function getCart(int $cartId): array
    {
        $domain = getenv('NGINX_BACKEND_DOMAIN');

        if ($domain === false) {
            $domain = 'nginx';
        }
        $response = $this->httpClient->request('GET', 'http://' . $domain . '/carts/' . $cartId);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch cart');
        }
        return json_decode($response->getContent(), true);
    }
}