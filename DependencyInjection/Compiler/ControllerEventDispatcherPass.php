<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use LogicException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * To be removed after FSiAdminBundle replaces the setEventDispatcher method with
 * a constructor argument.
 */
class ControllerEventDispatcherPass implements CompilerPassInterface
{
    private $controllerServicesIds = [
        'admin_translatable.controller.resource',
        'admin_translatable.controller.display',
        'admin_translatable.controller.list',
        'admin_translatable.controller.form',
        'admin_translatable.controller.batch'
    ];

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $canEventDispatcherBeSet = method_exists(
            '\FSi\Bundle\AdminBundle\Controller\ControllerAbstract',
            'setEventDispatcher'
        );
        if (!$canEventDispatcherBeSet) {
            return;
        }

        $eventDispatcher = new Reference('event_dispatcher');
        foreach ($this->controllerServicesIds as $id) {
            $definition = $container->getDefinition($id);
            if (!$definition) {
                throw new LogicException(sprintf(
                    'Cannot find controller service with id "%s"',
                    $id
                ));
            }

            $definition->addMethodCall('setEventDispatcher', [$eventDispatcher]);
        }
    }
}
