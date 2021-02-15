<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\ResourceRepositoryContext;
use FSi\Bundle\AdminTranslatableBundle\Repository\TranslatableMapBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ResourceRepositoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasExtension('fsi_resource_repository')) {
            $container->removeDefinition(TranslatableMapBuilder::class);
            $container->removeDefinition(ResourceRepositoryContext::class);
        }
    }
}
