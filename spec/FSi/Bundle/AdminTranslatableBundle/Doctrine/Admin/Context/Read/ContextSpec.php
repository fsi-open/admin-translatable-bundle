<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Read;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataSource\DataSource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;

class ContextSpec extends ObjectBehavior
{
    function let(
        TranslatableCRUDElement $element,
        HandlerInterface $handler,
        LocaleManager $localeManager,
        DataSource $datasource,
        DataGrid $datagrid
    ) {
        $this->beConstructedWith(array($handler), $localeManager);
        $element->createDataGrid()->willReturn($datagrid);
        $element->createDataSource()->willReturn($datasource);
        $this->setElement($element);
    }
    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_has_array_data(TranslatableCRUDElement $element)
    {
        $element->getOption('crud_list_title')->shouldBeCalled();

        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKey('datagrid_view');
        $this->getData()->shouldHaveKey('datasource_view');
        $this->getData()->shouldHaveKey('element');
        $this->getData()->shouldHaveKey('title');
        $this->getData()->shouldHaveKey('translatable_locale');
    }
}
