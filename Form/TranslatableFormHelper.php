<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;

use function array_key_exists;
use function get_class;
use function sprintf;

class TranslatableFormHelper
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

    public function isFormPropertyPathTranslatable(FormInterface $form): bool
    {
        $propertyPath = $form->getPropertyPath();
        if (null === $propertyPath) {
            return false;
        }

        $parent = $this->getFirstTranslatableParent($form);
        if (null === $parent) {
            return false;
        }

        if (false === $this->hasFormTranslatableProperty($parent, $propertyPath)) {
            return false;
        }

        return true;
    }

    /**
     * @param FormInterface $form
     * @return mixed
     */
    public function getFormNormDataLocale(FormInterface $form)
    {
        $classMetadata = $this->getFormTranslatableMetadata($form);

        if (null === $classMetadata || null === $form->getNormData()) {
            return null;
        }

        return $this->propertyAccessor->getValue(
            $form->getNormData(),
            $classMetadata->localeProperty
        );
    }

    public function isFormDataInCurrentLocale(FormInterface $form): bool
    {
        return $this->getFormNormDataLocale($form) === $this->translatableListener->getLocale();
    }

    public function getFirstTranslatableParent(FormInterface $form): ?FormInterface
    {
        for ($parent = $form; $parent !== null; $parent = $parent->getParent()) {
            if (true === $parent->getConfig()->getInheritData()) {
                continue;
            }

            $class = $parent->getConfig()->getDataClass();
            if (null === $class) {
                continue;
            }

            if (true === $this->isClassTranslatable($class)) {
                return $parent;
            }
        }

        return null;
    }

    private function isClassTranslatable(string $class): bool
    {
        if (null === $this->getManagerForClass($class)) {
            return false;
        }

        return $this->getTranslatableMetadata($class)->hasTranslatableProperties();
    }

    private function hasFormTranslatableProperty(
        FormInterface $form,
        PropertyPathInterface $propertyPath
    ): bool {
        $translatableMetadata = $this->getFormTranslatableMetadata($form);
        if (null === $translatableMetadata) {
            return false;
        }

        foreach ($translatableMetadata->getTranslatableProperties() as $translationProperties) {
            if (true === array_key_exists((string) $propertyPath, $translationProperties)) {
                return true;
            }
        }

        return false;
    }

    private function getFormTranslatableMetadata(FormInterface $form): ?ClassMetadata
    {
        if (null === $form->getConfig()->getDataClass()) {
            return null;
        }

        return $this->getTranslatableMetadata($form->getConfig()->getDataClass());
    }

    private function getManagerForClass(string $class): ?EntityManagerInterface
    {
        $objectManager = $this->managerRegistry->getManagerForClass($class);
        if (null === $objectManager) {
            return null;
        }

        if (false === $objectManager instanceof EntityManagerInterface) {
            throw new RuntimeException(
                sprintf("Expected %s but got %s", EntityManagerInterface::class, get_class($objectManager))
            );
        }

        return $objectManager;
    }

    private function getTranslatableMetadata(string $class): ClassMetadata
    {
        $classMetadata = $this->translatableListener->getExtendedMetadata($this->getManagerForClass($class), $class);
        if (false === $classMetadata instanceof ClassMetadata) {
            throw new RuntimeException(
                sprintf("Expected %s but got %s", ClassMetadata::class, get_class($classMetadata))
            );
        }

        return $classMetadata;
    }
}
