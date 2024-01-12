<?php

namespace App\EventListener;

use ErrorException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiExceptionsListener
{
    #[AsEventListener()]
    public function onNotFoundEvent(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $response = new Response();
        
        if($exception instanceof HttpException) {
            $content = json_encode([
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
            ]);
            $response->setContent($content);
        } else if ($exception instanceof ErrorException) {
            $content = json_encode([
                'code' => 500,
                'message' => $exception->getMessage(),
            ]);
            $response->setContent($content);
        } else {
            $content = json_encode([
                'code' => 500,
                'message' => $exception->getMessage(),
            ]);
            $response->setContent($content);
        }

        $event->setResponse($response);
    }
}