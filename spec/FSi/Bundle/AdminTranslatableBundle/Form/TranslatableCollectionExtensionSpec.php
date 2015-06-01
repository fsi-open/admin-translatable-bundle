<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Form;

use FSi\Bundle\AdminTranslatableBundle\Form\TranslatableCollectionListener;
use FSi\Bundle\AdminTranslatableBundle\Form\TranslatableFormHelper;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormBuilderInterface;

class TranslatableCollectionExtensionSpec extends ObjectBehavior
{
    public function let(TranslatableFormHelper $translatableFormHelper, TranslatableCollectionListener $listener)
    {
        $this->beConstructedWith($translatableFormHelper, $listener);
    }

    public function it_is_form_extension()
    {
        $this->beAnInstanceOf('Symfony\Component\Form\AbstractTypeExtension');
    }

    public function it_extends_collection()
    {
        $this->getExtendedType()->shouldReturn('collection');
    }

    public function it_adds_listener(FormBuilderInterface $builder, TranslatableCollectionListener $listener)
    {
        $builder->addEventSubscriber($listener)->shouldBeCalled();
        $this->buildForm($builder, []);
    }
}
