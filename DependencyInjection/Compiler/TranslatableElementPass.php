<?php

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TranslatableElementPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $adminElementsIds = array_keys($container->findTaggedServiceIds('admin.element'));

        foreach ($adminElementsIds as $elementId) {
            $adminElement = $container->findDefinition($elementId);
            $implements = class_implements($adminElement->getClass());

            if (in_array('FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableAwareInterface', $implements)) {
                $adminElement->addMethodCall('setLocaleManager', array($container->findDefinition('admin_translatable.manager.locale')));
            }
        }
    }
}
