<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Delete;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;

class ContextSpec extends ObjectBehavior
{
    function let(
        HandlerInterface $handler,
        TranslatableCRUDElement $element,
        FormFactory $factory,
        Form $form,
        FormView $view,
        LocaleManager $localeManager
    ) {
        $factory->createNamed('delete', 'form')->willReturn($form);
        $this->beConstructedWith(array($handler), $factory, $localeManager);
        $this->setElement($element);
        $form->createView()->willReturn($view);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_has_array_data()
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKey('element');
        $this->getData()->shouldHaveKey('indexes');
        $this->getData()->shouldHaveKey('form');
        $this->getData()->shouldHaveKey('translatable_locale');
    }
}
