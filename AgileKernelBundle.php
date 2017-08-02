<?php

namespace AgileKernelBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use AgileKernelBundle\DependencyInjection\Compiler\TinyMceExtensionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * AgileKernelBundle
 */
class AgileKernelBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $symfonyVersion = class_exists('Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterMappingsPass');
        $mappings       = [
            realpath(__DIR__.'/Resources/config/doctrine/model') => __NAMESPACE__.'\Model',
        ];

        if ($symfonyVersion && class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createYamlMappingDriver(
                $mappings,
                ['agile_kernel.model_manager_name'],
                false,
                ['AgileKernelBundle' => 'AgileKernelBundle\Model']
            ));
        }

        $container->addCompilerPass(new TinyMceExtensionPass());
    }
}
