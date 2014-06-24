<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Read;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Read\Context;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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

    function it_does_not_support_non_translatable_route(TranslatableCRUDElement $translatableElement)
    {
        $this->supports('fsi_admin_crud_list', $translatableElement)->shouldReturn(false);
    }
}
