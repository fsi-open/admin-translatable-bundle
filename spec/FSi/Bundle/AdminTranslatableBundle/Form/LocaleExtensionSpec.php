<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use FSi\Bundle\AdminTranslatableBundle\Form\TypeSolver;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class LocaleExtensionSpec extends ObjectBehavior
{
    function let(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->beConstructedWith($managerRegistry, $translatableListener, $propertyAccessor);
    }

    function it_is_form_extension()
    {
        $this->shouldBeAnInstanceOf(AbstractTypeExtension::class);
    }

    function it_extends_from_type()
    {
        $this->getExtendedType()->shouldReturn(
            TypeSolver::getFormType(FormType::class, 'form')
        );
    }

    function it_adds_itself_as_form_event_subscriber(FormBuilderInterface $formBuilder)
    {
        $formBuilder->addEventSubscriber($this)->shouldBeCalled();

        $this->buildForm($formBuilder, []);
    }

    function it_is_event_subscriber()
    {
        $this->shouldBeAnInstanceOf(EventSubscriberInterface::class);
    }

    function it_should_listen_to_post_submit_event()
    {
        $this->getSubscribedEvents()->shouldReturn([
            FormEvents::POST_SUBMIT => 'setTranslatableLocale'
        ]);
    }

    function it_sets_locale_on_translatable_data(
        ManagerRegistry $managerRegistry,
        EntityManagerInterface $objectManager,
        TranslatableListener $translatableListener,
        FormEvent $event,
        FormInterface $form,
        FormConfigInterface $formConfig,
        ClassMetadata $translatableClassMetadata,
        PropertyAccessorInterface $propertyAccessor,
        stdClass $entity
    ) {
        $translatableListener->getLocale()->willReturn('de');
        $event->getData()->willReturn($entity);
        $event->getForm()->willReturn($form);
        $form->getConfig()->willReturn($formConfig);
        $formConfig->getOption('data_class')->willReturn('TranslatableEntity');
        $managerRegistry->getManagerForClass('TranslatableEntity')->willReturn($objectManager);
        $translatableListener->getExtendedMetadata($objectManager, 'TranslatableEntity')
            ->willReturn($translatableClassMetadata);
        $translatableClassMetadata->hasTranslatableProperties()->willReturn(true);
        $translatableClassMetadata->localeProperty = 'locale';

        $propertyAccessor->setValue($entity, 'locale', 'de')->shouldBeCalled();

        $this->setTranslatableLocale($event);
    }

    function it_does_nothing_when_current_translatable_locale_is_not_set(
        TranslatableListener $translatableListener,
        FormEvent $event
    ) {
        $translatableListener->getLocale()->willReturn(null);

        $event->getForm()->shouldNotBeCalled();
        $event->getData()->shouldNotBeCalled();

        $this->setTranslatableLocale($event);
    }

    function it_does_nothing_when_form_has_no_data_class(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener,
        FormEvent $event,
        FormInterface $form,
        FormConfigInterface $formConfig
    ) {
        $translatableListener->getLocale()->willReturn('en');
        $event->getForm()->willReturn($form);
        $form->getConfig()->willReturn($formConfig);
        $formConfig->getOption('data_class')->willReturn(null);

        $managerRegistry->getManagerForClass(Argument::any())->shouldNotBeCalled();
        $event->getData()->shouldNotBeCalled();

        $this->setTranslatableLocale($event);
    }

    function it_does_nothing_when_form_data_is_not_translatable(
        ManagerRegistry $managerRegistry,
        EntityManagerInterface $objectManager,
        TranslatableListener $translatableListener,
        FormEvent $event,
        FormInterface $form,
        FormConfigInterface $formConfig,
        ClassMetadata $translatableClassMetadata
    ) {
        $translatableListener->getLocale()->willReturn('en');
        $event->getForm()->willReturn($form);
        $form->getConfig()->willReturn($formConfig);
        $formConfig->getOption('data_class')->willReturn('TranslatableEntity');
        $managerRegistry->getManagerForClass('TranslatableEntity')
            ->willReturn($objectManager);
        $translatableListener->getExtendedMetadata($objectManager, 'TranslatableEntity')
            ->willReturn($translatableClassMetadata);
        $translatableClassMetadata->hasTranslatableProperties()->willReturn(false);

        $event->getData()->shouldNotBeCalled();

        $this->setTranslatableLocale($event);
    }

    function it_does_nothing_when_form_data_has_no_translatable_properties(
        ManagerRegistry $managerRegistry,
        EntityManagerInterface $objectManager,
        TranslatableListener $translatableListener,
        FormEvent $event,
        FormInterface $form,
        FormConfigInterface $formConfig,
        ClassMetadata $translatableClassMetadata
    ) {
        $translatableListener->getLocale()->willReturn('en');
        $event->getForm()->willReturn($form);
        $form->getConfig()->willReturn($formConfig);
        $formConfig->getOption('data_class')->willReturn('TranslatableEntity');
        $managerRegistry->getManagerForClass('TranslatableEntity')
            ->willReturn($objectManager);
        $translatableListener->getExtendedMetadata($objectManager, 'TranslatableEntity')
            ->willReturn($translatableClassMetadata);
        $translatableClassMetadata->hasTranslatableProperties()->willReturn(false);

        $event->getData()->shouldNotBeCalled();

        $this->setTranslatableLocale($event);
    }
}
