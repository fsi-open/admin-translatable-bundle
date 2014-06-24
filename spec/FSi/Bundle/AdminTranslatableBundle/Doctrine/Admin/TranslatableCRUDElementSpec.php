<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TranslatableCRUDElementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminTranslatableBundle\spec\fixtures\TranslatableElement');
    }

    function it_implements_Translatable_Aware_Interface()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableAwareInterface');
    }

    function it_gets_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_translatable_crud_list');
    }
}
