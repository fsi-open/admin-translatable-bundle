<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class LocaleExtension extends AbstractTypeExtension implements EventSubscriberInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var TranslatableListener
     */
    private $translatableListener;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    public function __construct(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->translatableListener = $translatableListener;
        $this->propertyAccessor = $propertyAccessor;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => 'setTranslatableLocale'
        ];
    }

    public static function getExtendedTypes()
    {
        return [FormType::class];
    }

    public function getExtendedType()
    {
        return FormType::class;
    }

    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $formBuilder->addEventSubscriber($this);
    }

    public function setTranslatableLocale(FormEvent $event): void
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

    private function isCurrentLocaleSet(): bool
    {
        return null !== $this->getCurrentLocale();
    }

    private function getCurrentLocale(): ?string
    {
        return $this->translatableListener->getLocale();
    }

    private function formHasDataClass(FormEvent $event): bool
    {
        return null !== $this->getFormDataClass($event);
    }

    private function getFormDataClass(FormEvent $event): ?string
    {
        return $event->getForm()->getConfig()->getOption('data_class');
    }

    private function isFormDataClassTranslatable(FormEvent $event): bool
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

    private function getFormDataTranslatableMetadata(FormEvent $event): ?ClassMetadata
    {
        return $this->translatableListener->getExtendedMetadata(
            $this->getManagerForDataClass($event),
            $this->getFormDataClass($event)
        );
    }

    private function setFormDataLocale(FormEvent $event): void
    {
        $data = $event->getData();
        if (!$data) {
            return;
        }

        $this->propertyAccessor->setValue(
            $data,
            $this->getFormDataTranslatableMetadata($event)->localeProperty,
            $this->getCurrentLocale()
        );
    }

    private function getManagerForDataClass(FormEvent $event): ?ObjectManager
    {
        return $this->managerRegistry->getManagerForClass($this->getFormDataClass($event));
    }
}
