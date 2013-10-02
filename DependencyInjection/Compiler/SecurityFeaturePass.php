<?php

namespace Hautelook\SentryBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Enables features that require the security bundle.
 *
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
class SecurityFeaturePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has('security.context')) {
            $container
                ->getDefinition('hautelook_sentry.client')
                ->addMethodCall('addSubscriber', array(new Reference('hautelook_sentry.plugin.user')))
            ;
        }
    }
}
