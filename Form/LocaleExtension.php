<?php

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class LocaleExtension extends AbstractTypeExtension implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Bridge\Doctrine\ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var \FSi\DoctrineExtensions\Translatable\TranslatableListener
     */
    private $translatableListener;

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_SUBMIT => 'setTranslatableLocale'
        );
    }

    /**
     * @param \Symfony\Bridge\Doctrine\ManagerRegistry $managerRegistry
     * @param \FSi\DoctrineExtensions\Translatable\TranslatableListener $translatableListener
     * @param \Symfony\Component\PropertyAccess\PropertyAccessorInterface $propertyAccessor
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->translatableListener = $translatableListener;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @return string
     */
    public function getExtendedType()
    {
        return 'form';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $formBuilder->addEventSubscriber($this);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     */
    public function setTranslatableLocale(FormEvent $event)
    {
        if (!$this->isCurrentLocaleSet()) {
            return;
        }

        if (!$this->formHasDataClass($event)) {
            return;
        }

        if (!$this->isFormDataClassTranslatable($event)) {
            return;
        }

        $this->setFormDataLocale($event);
    }

    /**
     * @return bool
     */
    private function isCurrentLocaleSet()
    {
        return null !== $this->getCurrentLocale();
    }

    /**
     * @return mixed
     */
    private function getCurrentLocale()
    {
        return $this->translatableListener->getLocale();
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @return bool
     */
    private function formHasDataClass(FormEvent $event)
    {
        return null !== $this->getFormDataClass($event);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @return null|string
     */
    private function getFormDataClass(FormEvent $event)
    {
        return $event->getForm()->getConfig()->getOption('data_class');
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @return bool
     */
    private function isFormDataClassTranslatable(FormEvent $event)
    {
        if (null === $this->getManagerForDataClass($event)) {
            return false;
        }

        $translatableMetadata = $this->getFormDataTranslatableMetadata($event);
        if (null === $translatableMetadata) {
            return false;
        }

        return $translatableMetadata->hasTranslatableProperties();
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @return \FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata
     */
    private function getFormDataTranslatableMetadata(FormEvent $event)
    {
        return $this->translatableListener->getExtendedMetadata(
            $this->getManagerForDataClass($event),
            $this->getFormDataClass($event)
        );
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     */
    private function setFormDataLocale(FormEvent $event)
    {
        if (!$data = $event->getData()) {
            return;
        };

        $this->propertyAccessor->setValue(
            $data,
            $this->getFormDataTranslatableMetadata($event)->localeProperty,
            $this->getCurrentLocale()
        );
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @return \Doctrine\Common\Persistence\ObjectManager|null
     */
    private function getManagerForDataClass(FormEvent $event)
    {
        return $this->managerRegistry->getManagerForClass($this->getFormDataClass($event));
    }
}
