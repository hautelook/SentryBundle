<?php

namespace Hautelook\SentryBundle\DependencyInjection;

use Raven\Client;
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

        $clientOptions = $config['client_options'];
        $clientOptions['tags']['php_version'] = phpversion();
        $clientOptions['tags']['symfony_version'] = Kernel::VERSION;

        $container->setParameter('hautelook_sentry.client.options', $clientOptions);
        $container->setParameter('hautelook_sentry.error_handler.error', $config['error_handler']['error']);
        $container->setParameter('hautelook_sentry.error_handler.fatal_error', $config['error_handler']['fatal_error']);

        if (!$config['error_handler']['exception']) {
            $container->removeDefinition('hautelook_sentry.exception_listener');
        }
    }
}
