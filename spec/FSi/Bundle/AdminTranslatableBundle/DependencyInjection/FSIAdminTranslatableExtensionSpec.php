<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FSIAdminTranslatableExtensionSpec extends ObjectBehavior
{
    function it_is_bundle_extension()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\HttpKernel\DependencyInjection\Extension');
    }

    function it_implements_prepend_extension_interface()
    {
        $this->shouldImplement('Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface');
    }

    function it_prepends_admin_bundle_configuration(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('fsi_admin', array(
            'templates' => array(
                'base' => '@FSiAdminTranslatable/base.html.twig',
            )
        ))->shouldBeCalled();

        $this->prepend($container);
    }
}
