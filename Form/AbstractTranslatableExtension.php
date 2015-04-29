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
use FSi\Bundle\DoctrineExtensionsBundle\Resolver\FSiFilePathResolver;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use FSi\DoctrineExtensions\Uploadable\File;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

abstract class AbstractTranslatableExtension extends AbstractTypeExtension
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
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $propertyPath = $form->getPropertyPath();
        if (!$propertyPath) {
            return;
        }

        $parent = $this->getFirstTranslatableParent($form);
        if (!$parent) {
            return;
        }

        $view->vars['translatable'] = false;
        $view->vars['not_translated'] = false;

        if (!$this->hasFormTranslatableProperty($parent, $propertyPath)) {
            return;
        }

        $view->vars['translatable'] = true;
        if (!$this->hasCurrentValue($view, $form, $options) ||
            ($this->getFormNormDataLocale($parent) === $this->translatableListener->getLocale())) {
            return;
        }

        $view->vars['not_translated'] = true;
        $view->vars['label_attr']['data-default-locale'] = $this->getFormNormDataLocale($parent);
        $this->moveCurrentValueToDefaultLocaleValue($view, $form, $options);
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     * @return bool
     */
    abstract protected function hasCurrentValue(FormView $view, FormInterface $form, array $options);

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    abstract protected function moveCurrentValueToDefaultLocaleValue(FormView $view, FormInterface $form, array $options);

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

    /**
     * @param FormInterface $form
     * @return ClassMetadata|null
     */
    private function getFormTranslatableMetadata(FormInterface $form)
    {
        if (!($class = $form->getConfig()->getDataClass())) {
            return null;
        }

        return $this->getTranslatableMetadata($form->getConfig()->getDataClass());
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
     * @return FormInterface|null
     */
    private function getFirstTranslatableParent(FormInterface $form)
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
     * @param FormInterface $form
     * @return mixed
     */
    private function getFormNormDataLocale(FormInterface $form)
    {
        return $this->propertyAccessor->getValue(
            $form->getNormData(),
            $this->getFormTranslatableMetadata($form)->localeProperty
        );
    }
}
