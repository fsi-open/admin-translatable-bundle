<?php

namespace FSi\Bundle\AdminTranslatableBundle;

use FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler\MapBuilderPass;
use FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler\MenuBuilderPass;
use FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler\TranslatableElementPass;
use FSi\Bundle\AdminTranslatableBundle\DependencyInjection\FSIAdminTranslatableExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FSiAdminTranslatableBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MapBuilderPass());
        $container->addCompilerPass(new MenuBuilderPass());
        $container->addCompilerPass(new TranslatableElementPass(), PassConfig::TYPE_BEFORE_REMOVING);
    }

    /**
     * @return FSIAdminTranslatableExtension
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new FSIAdminTranslatableExtension();
        }

        return $this->extension;
    }
}
