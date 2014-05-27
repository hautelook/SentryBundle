<?php

namespace Hautelook\SentryBundle\DependencyInjection;

use Hautelook\SentryClient\Client;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
class HautelookSentryExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        foreach (array('sentry.xml', 'event_listener.xml') as $file) {
            $loader->load($file);
        }

        $container->setParameter('hautelook_sentry.client.options', $config['client_options']);
        $container->setParameter('hautelook_sentry.error_handler.error', $config['error_handler']['error']);
        $container->setParameter('hautelook_sentry.error_handler.fatal_error', $config['error_handler']['fatal_error']);

        if (!$config['error_handler']['exception']) {
            $container->removeDefinition('hautelook_sentry.exception_listener');
        }

        if (isset($config['files_base_path'])) {
            $container
                ->getDefinition('hautelook_sentry.factory.exception')
                ->replaceArgument(
                    0,
                    realpath($container->getParameterBag()->resolveValue($config['files_base_path']))
                )
            ;
        }

        $container
            ->getDefinition('hautelook_sentry.error_handler')
            ->replaceArgument(1, !$config['error_handler']['throw_handler_exceptions'])
        ;
    }
}
