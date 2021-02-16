<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\DataGrid\Extension\ColumnType;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class TranslatableSpec extends ObjectBehavior
{
    public function let(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener,
        EntityManagerInterface $manager,
        ClassMetadata $translatableMetadata
    ): void {
        $managerRegistry->getManagerForClass(Argument::type('string'))->willReturn($manager);
        $translatableListener->getExtendedMetadata($manager, Argument::type('string'))
            ->willReturn($translatableMetadata);
        $translatableListener->getLocale()->willReturn('en');

        $this->beConstructedWith($managerRegistry, $translatableListener, new PropertyAccessor());
    }

    public function it_is_column_type_extension(): void
    {
        $this->shouldBeAnInstanceOf(ColumnAbstractTypeExtension::class);
    }

    public function it_extends_action_column(): void
    {
        $this->getExtendedColumnTypes()->shouldReturn([
            'text',
            'boolean',
            'datetime',
            'money',
            'number',
            'fsi_file'
        ]);
    }

    public function it_sets_translatable_and_not_translated_to_false_when_column_has_no_properties_in_field_mapping(
        ColumnTypeInterface $column,
        CellViewInterface $view
    ): void {
        $column->getOption('field_mapping')->willReturn(['[property]']);

        $view->setAttribute('translatable', false)->shouldBeCalled();
        $view->setAttribute('not_translated', false)->shouldBeCalled();

        $this->buildCellView($column, $view);
    }

    public function it_sets_translatable_and_not_translated_to_false_when_column_has_no_translatable_property_in_field_mapping(
        ColumnTypeInterface $column,
        CellViewInterface $view,
        ClassMetadata $translatableMetadata
    ): void {
        $column->getOption('field_mapping')->willReturn(['property']);
        $data = (object) ['property' => 'value'];
        $view->getSource()->willReturn($data);
        $translatableMetadata->hasTranslatableProperties()->willReturn(false);

        $view->setAttribute('translatable', false)->shouldBeCalled();
        $view->setAttribute('not_translated', false)->shouldBeCalled();

        $this->buildCellView($column, $view);
    }

    public function it_sets_translatable_and_not_translated_to_false_when_column_has_only_non_translatable_properties_in_field_mapping(
        ColumnTypeInterface $column,
        CellViewInterface $view,
        ClassMetadata $translatableMetadata
    ): void {
        $column->getOption('field_mapping')->willReturn(['property']);
        $data = (object) ['property' => 'value'];
        $view->getSource()->willReturn($data);
        $translatableMetadata->hasTranslatableProperties()->willReturn(true);
        $translatableMetadata->getTranslatableProperties()
            ->willReturn(['translations' => ['translatable_property' => 'translation_property']]);

        $view->setAttribute('translatable', false)->shouldBeCalled();
        $view->setAttribute('not_translated', false)->shouldBeCalled();

        $this->buildCellView($column, $view);
    }

    public function it_sets_translatable_to_true_and_not_translated_to_false_when_column_has_translatable_property_in_field_mapping(
        ColumnTypeInterface $column,
        CellViewInterface $view,
        ClassMetadata $translatableMetadata
    ): void {
        $column->getOption('field_mapping')->willReturn(['translatable_property']);
        $data = (object) [
            'translatable_property' => 'value',
            'non_translatable_property' => 'value',
            'locale' => 'en'
        ];
        $view->getSource()->willReturn($data);
        $translatableMetadata->hasTranslatableProperties()->willReturn(true);
        $translatableMetadata->getTranslatableProperties()
            ->willReturn(['translations' => ['translatable_property' => 'translation_property']]);
        $translatableMetadata->localeProperty = 'locale';

        $view->setAttribute('translatable', true)->shouldBeCalled();
        $view->setAttribute('not_translated', false)->shouldBeCalled();

        $this->buildCellView($column, $view);
    }

    public function it_sets_translatable_to_true_and_not_translated_to_false_when_column_has_nested_translatable_property_in_field_mapping(
        ColumnTypeInterface $column,
        CellViewInterface $view,
        ClassMetadata $translatableMetadata
    ): void {
        $column->getOption('field_mapping')->willReturn(['nested_object.translatable_property']);
        $nested_object = (object) [
            'translatable_property' => 'value',
            'non_translatable_property' => 'value',
            'locale' => 'en'
        ];
        $data = (object) ['nested_object' => $nested_object];
        $view->getSource()->willReturn($data);
        $translatableMetadata->hasTranslatableProperties()->willReturn(true);
        $translatableMetadata->getTranslatableProperties()
            ->willReturn(['translations' => ['translatable_property' => 'translation_property']]);
        $translatableMetadata->localeProperty = 'locale';

        $view->setAttribute('translatable', true)->shouldBeCalled();
        $view->setAttribute('not_translated', false)->shouldBeCalled();

        $this->buildCellView($column, $view);
    }

    public function it_sets_translatable_and_not_translated_to_true_when_column_has_not_translated_translatable_property_in_field_mapping(
        ColumnTypeInterface $column,
        CellViewInterface $view,
        ClassMetadata $translatableMetadata
    ): void {
        $column->getOption('field_mapping')->willReturn(['translatable_property']);
        $data = (object) [
            'translatable_property' => 'value',
            'non_translatable_property' => 'value',
            'locale' => 'de'
        ];
        $view->getSource()->willReturn($data);
        $translatableMetadata->hasTranslatableProperties()->willReturn(true);
        $translatableMetadata->getTranslatableProperties()
            ->willReturn(['translations' => ['translatable_property' => 'translation_property']]);
        $translatableMetadata->localeProperty = 'locale';

        $view->setAttribute('translatable', true)->shouldBeCalled();
        $view->setAttribute('not_translated', true)->shouldBeCalled();

        $this->buildCellView($column, $view);
    }

    public function it_sets_translatable_and_not_translated_to_true_when_column_has_not_translated_nested_translatable_property_in_field_mapping(
        ColumnTypeInterface $column,
        CellViewInterface $view,
        ClassMetadata $translatableMetadata
    ): void {
        $column->getOption('field_mapping')->willReturn(['nested_object.translatable_property']);
        $nested_object = (object) [
            'translatable_property' => 'value',
            'non_translatable_property' => 'value',
            'locale' => 'de'
        ];
        $data = (object) ['nested_object' => $nested_object];
        $view->getSource()->willReturn($data);
        $translatableMetadata->hasTranslatableProperties()->willReturn(true);
        $translatableMetadata->getTranslatableProperties()
            ->willReturn(['translations' => ['translatable_property' => 'translation_property']]);
        $translatableMetadata->localeProperty = 'locale';

        $view->setAttribute('translatable', true)->shouldBeCalled();
        $view->setAttribute('not_translated', true)->shouldBeCalled();

        $this->buildCellView($column, $view);
    }
}
