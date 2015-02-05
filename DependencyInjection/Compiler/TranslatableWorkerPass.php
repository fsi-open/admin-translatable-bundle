<?php

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TranslatableWorkerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $productionLine = $container->findDefinition('admin.element.factory.production_line');
        $productionLine->addMethodCall(
            'addWorker',
            array($container->findDefinition('admin_translatable.factory.worker.translatable'))
        );
    }
}
