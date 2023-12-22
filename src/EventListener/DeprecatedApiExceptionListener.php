<?php

namespace App\EventListener;

use App\Exception\DeprecatedApiException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: 'kernel.exception', priority: 20)]
#[AsEventListener(event: 'kernel.exception', method: 'customHandler', priority: -100)]
class DeprecatedApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof DeprecatedApiException) {
            $newException = new DeprecatedApiException(
                \sprintf(
                    '%s %s',
                    __METHOD__,
                    $exception->getMessage()
                ),
                Response::HTTP_GONE
            );

            $event->setThrowable($newException);
        }
    }

    public function customHandler(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof DeprecatedApiException) {
            $response = new Response();
            $response->setContent($exception->getMessage());
            $response->setStatusCode($exception->getCode());
            $event->setResponse($response);
        }
    }
}
