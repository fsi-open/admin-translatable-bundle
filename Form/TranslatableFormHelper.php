<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;

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
        if (!$propertyPath) {
            return false;
        }

        $parent = $this->getFirstTranslatableParent($form);
        if (!$parent) {
            return false;
        }

        if (!$this->hasFormTranslatableProperty($parent, $propertyPath)) {
            return false;
        }

        return true;
    }

    public function getFormNormDataLocale(FormInterface $form)
    {
        $classMetadata = $this->getFormTranslatableMetadata($form);

        if (empty($classMetadata) || !$form->getNormData()) {
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
            if ($parent->getConfig()->getInheritData()) {
                continue;
            }

            if (!($class = $parent->getConfig()->getDataClass())) {
                continue;
            }

            if ($this->isClassTranslatable($class)) {
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

        $translatableMetadata = $this->getTranslatableMetadata($class);
        if (null === $translatableMetadata) {
            return false;
        }

        return $translatableMetadata->hasTranslatableProperties();
    }

    private function hasFormTranslatableProperty(
        FormInterface $form,
        PropertyPathInterface $propertyPath
    ): bool {
        $translatableMetadata = $this->getFormTranslatableMetadata($form);

        foreach ($translatableMetadata->getTranslatableProperties() as $translationProperties) {
            if (isset($translationProperties[(string) $propertyPath])) {
                return true;
            }
        }

        return false;
    }

    private function getFormTranslatableMetadata(FormInterface $form): ?ClassMetadata
    {
        if (!($class = $form->getConfig()->getDataClass())) {
            return null;
        }

        return $this->getTranslatableMetadata($form->getConfig()->getDataClass());
    }

    private function getManagerForClass(string $class): ?ObjectManager
    {
        return $this->managerRegistry->getManagerForClass($class);
    }

    private function getTranslatableMetadata(string $class): ClassMetadata
    {
        return $this->translatableListener->getExtendedMetadata(
            $this->getManagerForClass($class),
            $class
        );
    }
}
