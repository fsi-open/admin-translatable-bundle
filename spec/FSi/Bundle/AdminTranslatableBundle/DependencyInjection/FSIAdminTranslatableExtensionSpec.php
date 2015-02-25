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
}
