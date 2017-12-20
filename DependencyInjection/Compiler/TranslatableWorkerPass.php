<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TranslatableWorkerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $productionLine = $container->findDefinition('admin.element.factory.production_line');
        $productionLine->addMethodCall(
            'addWorker',
            [$container->findDefinition('admin_translatable.factory.worker.translatable')]
        );
    }
}
