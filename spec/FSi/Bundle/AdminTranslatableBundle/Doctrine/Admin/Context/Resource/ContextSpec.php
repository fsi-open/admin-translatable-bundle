<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Resource;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableResourceElement;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;

class ContextSpec extends ObjectBehavior
{
    function let(
        HandlerInterface $handler,
        TranslatableResourceElement $element,
        MapBuilder $builder,
        FormFactory $formFactory,
        FormBuilder $formBuilder,
        Form $form,
        LocaleManager $localeManager
    ) {
        $builder->getMap()->willReturn(array(
            'resources' => array()
        ));
        $element->getResourceFormOptions()->willReturn(array());
        $element->getKey()->willReturn('resources');
        $formFactory->createBuilder('form', array(),array())->willReturn($formBuilder);
        $formBuilder->getForm()->willReturn($form);

        $this->beConstructedWith(array($handler), $formFactory, $builder, $localeManager);
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_array_data(TranslatableResourceElement $element)
    {
        $element->getOption('title')->shouldBeCalled();

        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKey('form');
        $this->getData()->shouldHaveKey('title');
        $this->getData()->shouldHaveKey('element');
        $this->getData()->shouldHaveKey('translatable_locale');
    }
}
