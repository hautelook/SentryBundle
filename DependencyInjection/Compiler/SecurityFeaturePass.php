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
        $plugins = $container->getParameter('hautelook_sentry.plugins');

        if (!isset($plugins['user'])) {
            return;
        }
        
        if (!$container->has('security.context') && !$container->has('security.token_storage')) {
            return;
        }

        /**
         * When token_storage is missing, use deprecated security.context instead
         */
        if (!$container->has('security.token_storage')) {
            $container
                ->getDefinition('hautelook_sentry.factory.user')
                ->replaceArgument(0, new Reference('security.context'))
            ;
        }

        $container
            ->getDefinition('hautelook_sentry.client')
            ->addMethodCall('addSubscriber', array(new Reference('hautelook_sentry.plugin.user')))
        ;
    }
}
