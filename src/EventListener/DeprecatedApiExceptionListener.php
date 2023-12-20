<?php

namespace App\EventListener;

use App\Exception\DeprecatedApiException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: 'kernel.exception', priority: 20)]
#[AsEventListener(event: 'kernel.exception', method: 'customHandler', priority: 10)]
class DeprecatedApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof DeprecatedApiException) {
            var_dump('first');

            $response = new Response();
            $response->setContent($exception->getMessage());
            $response->setStatusCode(Response::HTTP_GONE);
            $event->setResponse($response);
        }
    }
    public function customHandler(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof DeprecatedApiException) {
            var_dump('second');

            $response = new Response();
            $response->setContent($exception->getMessage());
            $response->setStatusCode(Response::HTTP_GONE);
            $event->setResponse($response);
        }
    }
}
