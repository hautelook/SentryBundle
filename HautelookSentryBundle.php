<?php

namespace Hautelook\SentryBundle;

use Hautelook\SentryBundle\DependencyInjection\Compiler\SecurityFeaturePass;
use Hautelook\SentryClient\ErrorHandler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
class HautelookSentryBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();

        $registerErrorHandler = $this->container->getParameter('hautelook_sentry.error_handler.error');
        $registerFatalErrorHandler = $this->container->getParameter('hautelook_sentry.error_handler.fatal_error');

        if (!$registerErrorHandler && !$registerFatalErrorHandler) {
            return;
        }

        $errorHandler = $this->container->get('hautelook_sentry.error_handler'); /** @var $errorHandler ErrorHandler */
        if ($registerErrorHandler) {
            $errorHandler->registerErrorHandler();
        }
        if ($registerFatalErrorHandler) {
            $errorHandler->registerShutdownFunction();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SecurityFeaturePass());
    }
}
