<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Edit;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;

class ContextSpec extends ObjectBehavior
{
    protected $data;

    function let(
        TranslatableCRUDElement $element,
        Form $form,
        HandlerInterface $handler,
        LocaleManager $localeManager
    ) {
        $this->data = new \stdClass();
        $this->beConstructedWith(array($handler), $localeManager);
        $this->setElement($element);
        $element->createForm($this->data)->willReturn($form);
        $this->setEntity($this->data);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_has_array_data(TranslatableCRUDElement $element, DataIndexerInterface $indexer)
    {
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getIndex($this->data)->willReturn(1);
        $element->getOption('crud_edit_title')->shouldBeCalled();

        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKey('element');
        $this->getData()->shouldHaveKey('form');
        $this->getData()->shouldHaveKey('id');
        $this->getData()->shouldHaveKey('title');
        $this->getData()->shouldHaveKey('translatable_locale');
    }
}
