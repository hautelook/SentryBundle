<?php

namespace Hautelook\SentryBundle\EventListener;

use Hautelook\SentryClient\ErrorHandler;
use Symfony\Component\Console\Event\ConsoleExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener
{
    /**
     * @var ErrorHandler
     */
    private $errorHandler;

    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->errorHandler->handleException($event->getException());
    }

    public function onConsoleException(ConsoleExceptionEvent $event)
    {
        $this->errorHandler->handleException($event->getException());
    }
}
