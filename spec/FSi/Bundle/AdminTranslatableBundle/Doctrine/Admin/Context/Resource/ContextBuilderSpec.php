<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Resource;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Resource\Context;
use PhpSpec\ObjectBehavior;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableResourceElement;

class ContextBuilderSpec extends ObjectBehavior
{
    function let(Context $context)
    {
        $this->beConstructedWith($context);
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_resource_element(TranslatableResourceElement $element)
    {
        $this->supports('fsi_admin_translatable_resource', $element)->shouldReturn(true);
    }

    function it_build_context(TranslatableResourceElement $element, Context $context)
    {
        $context->setElement($element)->shouldBeCalled();

        $this->buildContext($element)->shouldReturn($context);
    }
}
