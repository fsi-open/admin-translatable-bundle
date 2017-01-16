<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests;

use FSi\Bundle\AdminBundle\Controller\ControllerAbstract;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ControllerEventDispatcherTest extends KernelTestCase
{
    /**
     * @var array
     */
    private $controllers = [
        'admin_translatable.controller.display',
        'admin_translatable.controller.list',
        'admin_translatable.controller.form',
        'admin_translatable.controller.batch',
        'admin_translatable.controller.resource'
    ];

    public function setUp()
    {
        self::bootKernel();
    }

    public function testEventDispatcherSet()
    {
        foreach ($this->controllers as $serviceId) {
            $this->assertEventDispatcherSet($serviceId);
        }
    }

    private function assertEventDispatcherSet($serviceId)
    {
        /* @var $service ControllerAbstract */
        $controllerService = self::$kernel->getContainer()->get($serviceId);
        $reflection = new ReflectionClass('\FSi\Bundle\AdminBundle\Controller\ControllerAbstract');
        $property = $reflection->getProperty('eventDispatcher');
        $property->setAccessible(true);
        $this->assertInstanceOf(
            '\Symfony\Component\EventDispatcher\EventDispatcherInterface',
            $property->getValue($controllerService)
        );
    }
}
