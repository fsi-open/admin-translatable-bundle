<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\DataGrid\Extension\ColumnType;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

class Translatable extends ColumnAbstractTypeExtension
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
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

    public function __construct(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->translatableListener = $translatableListener;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function getExtendedColumnTypes()
    {
        return array(
            'text',
            'boolean',
            'datetime',
            'money',
            'number',
            'fsi_file'
        );
    }

    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {
        $translatable = false;
        $notTranslated = false;

        foreach ($column->getOption('field_mapping') as $propertyPath) {
            if ($this->isPropertyPathTranslatable($view, $propertyPath)) {
                $translatable = true;

                if ($this->isPropertyPathTranslated($view, $propertyPath)) {
                    $notTranslated = true;
                }
            }
        }

        $view->setAttribute('translatable', $translatable);
        $view->setAttribute('not_translated', $notTranslated);
    }

    /**
     * @param string $propertyPath
     * @return null|\Symfony\Component\PropertyAccess\PropertyPath
     */
    private function validatePropertyPath($propertyPath)
    {
        $propertyPath = new PropertyPath($propertyPath);
        if ($propertyPath->isProperty($propertyPath->getLength() - 1)) {
            return $propertyPath;
        } else {
            return null;
        }
    }

    /**
     * @param \FSi\Component\DataGrid\Column\CellViewInterface $view
     * @param \Symfony\Component\PropertyAccess\PropertyPathInterface $propertyPath
     * @return object
     */
    private function getMostNestedObject(CellViewInterface $view, PropertyPathInterface $propertyPath)
    {
        if ($propertyPath->getLength() > 1) {
            return $this->propertyAccessor->getValue($view->getSource(), $propertyPath->getParent()->__toString());
        } else {
            return $view->getSource();
        }
    }

    /**
     * @param \Symfony\Component\PropertyAccess\PropertyPathInterface $propertyPath
     * @return string
     */
    private function getMostNestedProperty(PropertyPathInterface $propertyPath)
    {
        return $propertyPath->getElement($propertyPath->getLength() - 1);
    }

    /**
     * @param \FSi\Component\DataGrid\Column\CellViewInterface $view
     * @param \Symfony\Component\PropertyAccess\PropertyPathInterface $propertyPath
     * @return \FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata
     */
    private function getTranslatableMetadata(CellViewInterface $view, PropertyPathInterface $propertyPath)
    {
        $object = $this->getMostNestedObject($view, $propertyPath);

        return $this->getObjectTranslatableMetadata($object);
    }

    /**
     * @param object $object
     * @return \FSi\Component\Metadata\ClassMetadataInterface
     */
    private function getObjectTranslatableMetadata($object)
    {
        $class = get_class($object);
        $manager = $this->managerRegistry->getManagerForClass($class);

        return $this->translatableListener->getExtendedMetadata($manager, $class);
    }

    /**
     * @param \FSi\Component\DataGrid\Column\CellViewInterface $view
     * @param string $propertyPath
     * @return bool
     */
    private function isPropertyPathTranslated(CellViewInterface $view, $propertyPath)
    {
        if (!($propertyPath = $this->validatePropertyPath($propertyPath))) {
            return false;
        }

        return $this->getObjectLocale($view, $propertyPath) !== $this->translatableListener->getLocale();
    }

    /**
     * @param \FSi\Component\DataGrid\Column\CellViewInterface $view
     * @param string $propertyPath
     * @return bool
     */
    private function isPropertyPathTranslatable(CellViewInterface $view, $propertyPath)
    {
        if (!($propertyPath = $this->validatePropertyPath($propertyPath))) {
            return false;
        }

        $translatableMetadata = $this->getTranslatableMetadata($view, $propertyPath);
        if (!$translatableMetadata->hasTranslatableProperties()) {
            return false;
        }

        $property = $this->getMostNestedProperty($propertyPath);
        foreach ($translatableMetadata->getTranslatableProperties() as $translations => $translatableProperties) {
            if (isset($translatableProperties[$property])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \FSi\Component\DataGrid\Column\CellViewInterface $view
     * @param $propertyPath
     * @return mixed
     */
    private function getObjectLocale(CellViewInterface $view, $propertyPath)
    {
        $object = $this->getMostNestedObject($view, $propertyPath);
        $translatableMetadata = $this->getObjectTranslatableMetadata($object);

        return $this->propertyAccessor->getValue($object, $translatableMetadata->localeProperty);
    }
}
