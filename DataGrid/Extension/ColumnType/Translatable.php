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
use Doctrine\ORM\EntityManagerInterface;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

use function get_class;

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
            if (true === $this->isPropertyPathTranslatable($view, $propertyPath)) {
                $translatable = true;

                if (true === $this->isPropertyPathTranslated($view, $propertyPath)) {
                    $notTranslated = true;
                }
            }
        }

        $view->setAttribute('translatable', $translatable);
        $view->setAttribute('not_translated', $notTranslated);
    }

    private function getMostNestedObject(
        CellViewInterface $view,
        PropertyPathInterface $propertyPath
    ) {
        if (1 < $propertyPath->getLength()) {
            return $this->propertyAccessor->getValue(
                $view->getSource(),
                (string) $propertyPath->getParent()
            );
        }

        return $view->getSource();
    }

    private function isPropertyPathTranslatable(CellViewInterface $view, string $stringPath): bool
    {
        $propertyPath = $this->transformPathToPropertyPathObjectIfValid($stringPath);
        if (null === $propertyPath) {
            return false;
        }

        $translatableMetadata = $this->getObjectTranslatableMetadata($this->getMostNestedObject($view, $propertyPath));
        if (null === $translatableMetadata || false === $translatableMetadata->hasTranslatableProperties()) {
            return false;
        }

        $property = $this->getMostNestedProperty($propertyPath);
        foreach ($translatableMetadata->getTranslatableProperties() as $translatableProperties) {
            if (isset($translatableProperties[$property])) {
                return true;
            }
        }

        return false;
    }

    private function isPropertyPathTranslated(CellViewInterface $view, string $stringPath): bool
    {
        $propertyPath = $this->transformPathToPropertyPathObjectIfValid($stringPath);
        if (null === $propertyPath) {
            return false;
        }

        $locale = $this->translatableListener->getLocale();
        if (null === $locale) {
            return false;
        }

        $objectLocale = $this->getObjectLocale($view, $propertyPath);
        if (null === $objectLocale) {
            return false;
        }

        return $objectLocale !== $locale;
    }

    private function getObjectLocale(CellViewInterface $view, $propertyPath): ?string
    {
        $object = $this->getMostNestedObject($view, $propertyPath);
        $translatableMetadata = $this->getObjectTranslatableMetadata($object);
        if (null === $translatableMetadata) {
            return null;
        }

        return $this->propertyAccessor->getValue($object, $translatableMetadata->localeProperty);
    }

    private function getObjectTranslatableMetadata($object): ?ClassMetadata
    {
        $class = get_class($object);
        $manager = $this->managerRegistry->getManagerForClass($class);
        if (false === $manager instanceof EntityManagerInterface) {
            return null;
        }

        $classMetadata = $this->translatableListener->getExtendedMetadata($manager, $class);
        if (false === $classMetadata instanceof ClassMetadata) {
            throw new RuntimeException(
                sprintf("Expected %s but got %s", ClassMetadata::class, get_class($classMetadata))
            );
        }

        return $classMetadata;
    }

    private function transformPathToPropertyPathObjectIfValid(string $stringPath): ?PropertyPath
    {
        $propertyPath = new PropertyPath($stringPath);
        return true === $propertyPath->isProperty($propertyPath->getLength() - 1)
            ? $propertyPath
            : null
        ;
    }

    private function getMostNestedProperty(PropertyPathInterface $propertyPath): string
    {
        return $propertyPath->getElement($propertyPath->getLength() - 1);
    }
}
