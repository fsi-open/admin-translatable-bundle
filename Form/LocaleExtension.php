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
use Doctrine\ORM\EntityManagerInterface;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use RuntimeException;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

use function get_class;
use function sprintf;

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
        if (false === $this->isCurrentLocaleSet()) {
            return;
        }

        if (false === $this->formHasDataClass($event)) {
            return;
        }

        if (false === $this->isFormDataClassTranslatable($event)) {
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

        return $this->getFormDataTranslatableMetadata($event)->hasTranslatableProperties();
    }

    private function getFormDataTranslatableMetadata(FormEvent $event): ClassMetadata
    {
        $entityManager = $this->getManagerForDataClass($event);
        if (false === $entityManager instanceof EntityManagerInterface) {
            throw new RuntimeException(
                sprintf(
                    "Expected instance of %s but got instance of %s",
                    EntityManagerInterface::class,
                    get_class($entityManager)
                )
            );
        }

        $classMetadata = $this->translatableListener->getExtendedMetadata(
            $entityManager,
            $this->getFormDataClass($event)
        );

        if (false === $classMetadata instanceof ClassMetadata) {
            throw new RuntimeException(
                sprintf(
                    "Expected instance of %s but got instance of %s",
                    ClassMetadata::class,
                    get_class($classMetadata)
                )
            );
        }

        return $classMetadata;
    }

    private function setFormDataLocale(FormEvent $event): void
    {
        $data = $event->getData();
        if (null === $data) {
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
