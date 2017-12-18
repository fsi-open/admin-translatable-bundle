<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\DataGrid\Extension\ColumnType;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\DoctrineExtensions\Metadata\ClassMetadataInterface;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

class Translatable extends ColumnAbstractTypeExtension
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

    public function getExtendedColumnTypes(): array
    {
        return [
            'text',
            'boolean',
            'datetime',
            'money',
            'number',
            'fsi_file'
        ];
    }

    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view): void
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
     * @return null|PropertyPath
     */
    private function validatePropertyPath(string $propertyPath): ?PropertyPath
    {
        $propertyPath = new PropertyPath($propertyPath);
        return $propertyPath->isProperty($propertyPath->getLength() - 1)
            ? $propertyPath
            : null
        ;
    }

    private function getMostNestedObject(
        CellViewInterface $view,
        PropertyPathInterface $propertyPath
    ) {
        return $propertyPath->getLength() > 1
            ? $this->propertyAccessor->getValue(
                $view->getSource(),
                (string) $propertyPath->getParent()
            )
            : $view->getSource()
        ;

    }

    private function getMostNestedProperty(PropertyPathInterface $propertyPath): string
    {
        return $propertyPath->getElement($propertyPath->getLength() - 1);
    }

    private function getTranslatableMetadata(
        CellViewInterface $view,
        PropertyPathInterface $propertyPath
    ): ClassMetadata {
        $object = $this->getMostNestedObject($view, $propertyPath);

        return $this->getObjectTranslatableMetadata($object);
    }

    private function getObjectTranslatableMetadata($object): ClassMetadataInterface
    {
        $class = get_class($object);
        $manager = $this->managerRegistry->getManagerForClass($class);

        return $this->translatableListener->getExtendedMetadata($manager, $class);
    }

    private function isPropertyPathTranslated(CellViewInterface $view, string $propertyPath): bool
    {
        $propertyPath = $this->validatePropertyPath($propertyPath);
        if (!$propertyPath) {
            return false;
        }

        return $this->getObjectLocale($view, $propertyPath) !== $this->translatableListener->getLocale();
    }

    private function isPropertyPathTranslatable(CellViewInterface $view, string $propertyPath): bool
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

    private function getObjectLocale(CellViewInterface $view, $propertyPath)
    {
        $object = $this->getMostNestedObject($view, $propertyPath);
        $translatableMetadata = $this->getObjectTranslatableMetadata($object);

        return $this->propertyAccessor->getValue($object, $translatableMetadata->localeProperty);
    }
}
