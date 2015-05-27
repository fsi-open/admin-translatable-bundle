<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    /**
     * @param ManagerRegistry $managerRegistry
     * @param TranslatableListener $translatableListener
     * @param PropertyAccessorInterface $propertyAccessor
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
     * @param FormInterface $form
     * @return bool
     */
    public function isFormForTranslatableProperty(FormInterface $form)
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

    /**
     * @param FormInterface $form
     * @return mixed
     */
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

    /**
     * @param FormInterface $form
     * @return bool
     */
    public function isFormDataInCurrentLocale(FormInterface $form)
    {
        return $this->getFormNormDataLocale($form) === $this->translatableListener->getLocale();
    }

    /**
     * @param FormInterface $form
     * @return FormInterface|null
     */
    public function getFirstTranslatableParent(FormInterface $form)
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

    /**
     * @param string $class
     * @return bool
     */
    private function isClassTranslatable($class)
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

    /**
     * @param FormInterface $form
     * @param PropertyPathInterface $propertyPath
     * @return bool
     */
    private function hasFormTranslatableProperty(FormInterface $form, PropertyPathInterface $propertyPath)
    {
        $translatableMetadata = $this->getFormTranslatableMetadata($form);

        foreach ($translatableMetadata->getTranslatableProperties() as $translationProperties) {
            if (isset($translationProperties[(string) $propertyPath])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param FormInterface $form
     * @return ClassMetadata|null
     */
    private function getFormTranslatableMetadata(FormInterface $form)
    {
        if (!($class = $form->getConfig()->getDataClass())) {
            return;
        }

        return $this->getTranslatableMetadata($form->getConfig()->getDataClass());
    }

    /**
     * @param string $class
     * @return ObjectManager|null
     */
    private function getManagerForClass($class)
    {
        return $this->managerRegistry->getManagerForClass($class);
    }

    /**
     * @param string $class
     * @return ClassMetadata
     */
    private function getTranslatableMetadata($class)
    {
        return $this->translatableListener->getExtendedMetadata(
            $this->getManagerForClass($class),
            $class
        );
    }
}
