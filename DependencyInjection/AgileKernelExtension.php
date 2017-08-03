<?php

namespace AgileKernelBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * AgileKernelExtension
 */
class AgileKernelExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('agile_kernel', $config);

        $this->loadMailer($loader, $container, $config['mailer']);

        $container->setParameter('agile_kernel.google_map_api_key', $config['google_map_api_key']);

        $container->setParameter('agile_kernel.logout_route', $config['logout_route']);
        $twigBaseExtension = $container->getDefinition('agile_kernel.twig.extension.base');
        $twigBaseExtension->addMethodCall('setGlobal', [
            'logout_route',
            '%agile_kernel.logout_route%',
        ]);

        if ($config['locale']['enabled']) {
            $this->loadLocale($container, $config['locale']);
        }

        $this->loadForm($loader, $container, $config['form']);
        $this->mapModel($container, $config['model'], 'object_reference');
        $this->mapModel($container, $config['model'], 'object_history');
        $loader->load('listener.yml');
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $model
     * @param                  $field
     */
    private function mapModel(ContainerBuilder $container, array $model, $field)
    {
        $container->setParameter('agile_kernel.model.'.$field.'.class', $model[$field.'_class']);
        $container->setParameter('agile_kernel.model.'.$field.'.table', $model[$field.'_table']);
    }

    /**
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        $configs = $container->getExtensionConfig($this->getAlias());
        $this->processConfiguration(new Configuration(), $configs);
        if (isset($bundles['TwigBundle'])) {
            $this->configureTwigBundle($container);
        }
    }

    /**
     * @param LoaderInterface  $loader
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function loadMailer(LoaderInterface $loader, ContainerBuilder $container, array $config)
    {
        $container->setParameter('agile_kernel.mailer.sender_address', $config['sender_address']);
        $container->setParameter('agile_kernel.mailer.sender_name', $config['sender_name']);
        $loader->load('mailer.yml');
        $definition = new DefinitionDecorator('agile_kernel.mailer.abstract');
        $definition
            ->setClass('%agile_kernel.mailer.default.class%')
            ->setPublic(true)
            ->addArgument(new Reference('mailer'))
            ->addArgument($container->getParameter('mailer_host'))
            ->addArgument($container->getParameter('agile.project_name'))
            ->addArgument($container->getParameter('agile.host_env'));
        $container->setDefinition('agile_kernel.mailer.default', $definition);

        $container->setAlias('agile_kernel.mailer', 'agile_kernel.mailer.default');
    }

    private function loadLocale(ContainerBuilder $container, array $config)
    {
        $container->setParameter('agile_kernel.locales', $config['locales']);
        $twigBaseExtension = $container->getDefinition('agile_kernel.twig.extension.base');
        $twigBaseExtension->addMethodCall('setGlobal', [
            'locales',
            '%agile_kernel.locales%',
        ]);
    }

    private function loadForm(LoaderInterface $loader, ContainerBuilder $container, array $config)
    {
        $container->setParameter('agile_kernel.form.tinymce.plugins', $config['tinymce']['plugins']);
        $container->setParameter('agile_kernel.form.tinymce.content_css', $config['tinymce']['content_css']);
        $container->setParameter('agile_kernel.form.tinymce.configuration', $config['tinymce']['configuration']);
        $container->setParameter('agile_kernel.form.tinymce.plugin.agile_templates.templates', $config['tinymce']['templates']);
        $loader->load('form.yml');
    }

    private function configureTwigBundle(ContainerBuilder $container)
    {
        $bundles       = $container->getParameter('kernel.bundles');
        $formTemplates = [];

        $formTemplates[] = 'bootstrap_3_layout.html.twig';
        $formTemplates[] = 'AgileKernelBundle:form:theme.html.twig';

        if (isset($bundles['AgileFileUploadBundle'])) {
            $formTemplates[] = 'AgileFileUploadBundle:form:theme.html.twig';
        }

        foreach (array_keys($container->getExtensions()) as $name) {
            switch ($name) {
                case 'twig':
                    $container->prependExtensionConfig(
                        $name,
                        [
                            'form_themes' => $formTemplates,
                        ]
                    );
                    break;
            }
        }
    }
}
