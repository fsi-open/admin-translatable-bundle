<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\DataGrid\Extension\ColumnType;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Routing\RouterInterface;

class ActionSpec extends ObjectBehavior
{
    function let(
        LocaleManager $localeManager,
        RouterInterface $router
    ) {
        $this->beConstructedWith($localeManager, $router);
    }

    function it_is_column_type_extension()
    {
        $this->shouldBeAnInstanceOf('FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension');
    }

    function it_extends_action_column()
    {
        $this->getExtendedColumnTypes()->shouldReturn(array('action'));
    }
}
