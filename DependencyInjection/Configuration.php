<?php

namespace Hautelook\SentryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hautelook_sentry');

        $rootNode
            ->children()
                ->arrayNode('error_handler')
                    ->canBeEnabled()
                    ->beforeNormalization()
                        ->always(function($v) {
                            // This is a workaround for something that should work with ->canBeEnabled()
                            if (!is_array($v)) {
                                $v = array('enabled' => $v);
                            }

                            if (!$v['enabled']) {
                                $v['exception'] = false;
                                $v['error'] = false;
                                $v['fatal_error'] = false;
                            }

                            return $v;
                        })
                    ->end()
                    ->children()
                        ->booleanNode('exception')->defaultTrue()->end()
                        ->booleanNode('error')->defaultTrue()->end()
                        ->booleanNode('fatal_error')->defaultTrue()->end()
                    ->end()
                    ->scalarNode('files_base_path')->end()
                ->end()
            ->end()
        ;

        $ravenConfiguration = new \Hautelook\SentryClient\Configuration();
        $ravenConfiguration->addConfiguration(
            $rootNode
                ->children()
                ->arrayNode('client_options')
                ->isRequired()
        );

        return $treeBuilder;
    }
}
