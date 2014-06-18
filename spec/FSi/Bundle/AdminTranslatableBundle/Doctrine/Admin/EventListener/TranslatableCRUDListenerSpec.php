<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\EventListener;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Doctrine\Common\Persistence\ObjectManager;
use FSi\Component\Metadata\ClassMetadataInterface;

class TranslatableCRUDListenerSpec extends ObjectBehavior
{
    function let(
        LocaleManager $localeManager,
        ManagerRegistry $managerRegistry,
        EntityManager $entityManager,
        EventManager $eventManager,
        TranslatableListener $translatableListener,
        PropertyAccessor $propertyAccessor
    ) {
        $managerRegistry->getManager()->willReturn($entityManager);
        $entityManager->getEventManager()->willReturn($eventManager);
        $eventManager->getListeners()
            ->willReturn(array(
                'preFlush' => array(
                    $translatableListener
                )
            ));

        $this->beConstructedWith($localeManager, $managerRegistry, $propertyAccessor);
    }

    function it_implement_Event_Subscriber_Interface()
    {
        $this->beAnInstanceOf('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    function it_subscribe_crud_entity_pre_save_event()
    {
        $this->getSubscribedEvents()->shouldReturn(
            array(
                CRUDEvents::CRUD_EDIT_ENTITY_PRE_SAVE => 'setFormDataLocale',
            )
        );
    }

    function it_sets_locale_for_translatable_element_form_data(
        FormEvent $event,
        TranslatableCRUDElement $translatableCRUDElement,
        Form $form,
        ManagerRegistry $managerRegistry,
        ObjectManager $objectManager,
        FormConfigInterface $formConfig,
        LocaleManager $localeManager,
        TranslatableListener $translatableListener,
        ClassMetadataInterface $classMetadata,
        PropertyAccessor $propertyAccessor
    ) {
        $event->getElement()->willReturn($translatableCRUDElement);
        $event->getForm()->willReturn($form);
        $form->getData()->willReturn(new \stdClass());

        $form->getConfig()->willReturn($formConfig);
        $formConfig->getDataClass()->willReturn('FSi\Bundle\AdminTranslatableBundle\TranslatableEntity');

        $managerRegistry->getManagerForClass('FSi\Bundle\AdminTranslatableBundle\TranslatableEntity')->willReturn($objectManager);

        $translatableListener->getExtendedMetadata(
            $objectManager,
            'FSi\Bundle\AdminTranslatableBundle\TranslatableEntity'
        )->willReturn($classMetadata);

        $classMetadata->localeProperty = "locale";
        $localeManager->getLocale()->willReturn('en');

        $propertyAccessor->setValue(
            Argument::type('stdClass'),
            $classMetadata->localeProperty,
            'en'
        )->shouldBeCalled();

        $this->setFormDataLocale($event);
    }
}
