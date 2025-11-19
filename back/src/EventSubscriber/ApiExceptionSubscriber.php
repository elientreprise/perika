<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

readonly class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private bool $debug = false)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $status = 500;
        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();
        } elseif ($exception instanceof AuthenticationException) {
            $status = 401;
        } elseif ($exception instanceof ValidationFailedException) {
            $status = 422;
        }

        $error = [
            'message' => $exception->getMessage(),
            'code' => $status,
        ];

        if ($exception instanceof ValidationFailedException) {
            $error['violations'] = [];
            foreach ($exception->getViolations() as $violation) {
                $error['violations'][] = [
                    'property' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }
        }

        if ($this->debug) {
            $error['debug'] = [
                'exception' => get_class($exception),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        $response = new JsonResponse(['error' => $error], $status);
        $event->setResponse($response);
    }
}
