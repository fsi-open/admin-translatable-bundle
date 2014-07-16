<?php

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use PhpSpec\ObjectBehavior;
use Doctrine\Common\Persistence\ManagerRegistry;

class TranslatableResourceElementSpec extends ObjectBehavior
{
    function let(
        ManagerRegistry $registry,
        LocaleManager $localeManager,
        array $options
    ) {
        $this->beConstructedWith($options, $localeManager);

        $this->beAnInstanceOf('FSi\Bundle\AdminTranslatableBundle\spec\fixtures\ResourceTranslatableElement');
        $this->setManagerRegistry($registry);
    }

    function it_implements_Translatable_Aware_Interface()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableAwareInterface');
    }

    function it_gets_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_translatable_resource');
    }
}
