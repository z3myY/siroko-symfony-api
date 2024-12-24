<?php

declare(strict_types=1);

namespace App\EventListener\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionHandler
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $message = 'The requested URL was not found on this server.';
        $response = new JsonResponse();
        

        if ($exception instanceof HttpExceptionInterface) {
            $response->setData([
                'error' => $message,
                'status_code' => $exception->getStatusCode()
            ]);
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}