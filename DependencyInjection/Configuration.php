<?php

namespace AgileKernelBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * AgileKernelBundle Configuration
 */
class Configuration implements ConfigurationInterface
{
    static public $globals = [
        'host_env',
        'project_domain',
        'project_url',
        'project_name',
        'project_title',
    ];

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('agile_kernel');

        $children = $rootNode->children();

        foreach (self::$globals as $key) {
            $children->scalarNode($key)->defaultValue('%agile.'.$key.'%')->cannotBeEmpty()->end();
        }

        $rootNode
            ->children()
                ->arrayNode('locale')
                    ->canBeDisabled()
                    ->children()
                        ->arrayNode('locales')
                            ->treatNullLike([])
                            ->prototype('scalar')->end()
                            ->defaultValue(['%locale%'])
                        ->end()
                        ->scalarNode('default_locale')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue('%locale%')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('mailer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('sender_address')->defaultValue('no-reply@%agile.project_domain%')->end()
                        ->scalarNode('sender_name')->defaultValue('%agile.project_title%')->end()
                    ->end()
                ->end()
                ->arrayNode('model')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('object_reference_class')->defaultValue('AgileKernelBundle\Model\ObjectReference')->cannotBeEmpty()->end()
                        ->scalarNode('object_reference_table')->defaultValue('object_references')->cannotBeEmpty()->end()
                        ->scalarNode('object_history_class')->defaultValue('AgileKernelBundle\Model\ObjectHistory')->cannotBeEmpty()->end()
                        ->scalarNode('object_history_table')->defaultValue('object_histories')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('tinymce')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('content_css')
                                    ->treatNullLike([])
                                    ->prototype('scalar')->end()
                                    ->example(['bundles/acmedemo/css/style.css'])
                                ->end()
                                ->arrayNode('configuration')
                                    ->addDefaultsIfNotSet()
                                ->end()
                                ->arrayNode('templates')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('title')->end()
                                            ->scalarNode('content')->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('plugins')
                                    ->defaultValue(['agile_templates' => 'bundles/agilekernel/tinymce/agile_templates/plugin.min.js'])
                                    ->prototype('scalar')->end()
                                    ->example(['my_plugin' => 'bundles/acmedemo/tinymce/my_plugin/plugin.min.js'])
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('logout_route')->defaultValue('fos_user_security_logout')->cannotBeEmpty()->end()
                ->scalarNode('google_map_api_key')->defaultNull()->cannotBeEmpty()->end()
            ->end();
        return $treeBuilder;
    }
}
