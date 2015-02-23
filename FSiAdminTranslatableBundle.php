<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle;

use FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler\MapBuilderPass;
use FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler\ResourceRepositoryPass;
use FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler\TranslatableWorkerPass;
use FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler\TwigGlobals;
use FSi\Bundle\AdminTranslatableBundle\DependencyInjection\FSIAdminTranslatableExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FSiAdminTranslatableBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MapBuilderPass());
        $container->addCompilerPass(new ResourceRepositoryPass());
        $container->addCompilerPass(new TranslatableWorkerPass());
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
