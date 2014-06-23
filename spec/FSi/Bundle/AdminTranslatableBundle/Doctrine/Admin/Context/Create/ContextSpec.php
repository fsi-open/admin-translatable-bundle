<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Create;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;

class ContextSpec extends ObjectBehavior
{
    function let(
        TranslatableCRUDElement $element,
        Form $form,
        HandlerInterface $handler,
        LocaleManager $localeManager
    ) {
        $this->beConstructedWith(array($handler), $localeManager);
        $element->createForm(Argument::any())->willReturn($form);
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_array_data(TranslatableCRUDElement $element)
    {
        $element->getOption('crud_create_title')->shouldBeCalled();

        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKey('element');
        $this->getData()->shouldHaveKey('form');
        $this->getData()->shouldHaveKey('title');
        $this->getData()->shouldHaveKey('translatable_locale');
    }
}
