<?php

namespace AgileKernelBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class TinyMceExtensionPass
 *
 * @package AgileKernelBundle\DependencyInjection\Compiler
 */
class TinyMceExtensionPass implements CompilerPassInterface
{
    const TAG_NAME = 'agile_kernel.tinymce_extension';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('agile_kernel.form.tinymce.extension_manager')) {
            return;
        }

        $definition = $container->getDefinition('agile_kernel.form.tinymce.extension_manager');

        foreach ($container->findTaggedServiceIds(self::TAG_NAME) as $serviceId => $tag) {
            $definition->addMethodCall('addExtension', [new Reference($serviceId)]);
        }
    }
}
