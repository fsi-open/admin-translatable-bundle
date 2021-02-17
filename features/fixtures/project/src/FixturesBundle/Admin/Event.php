<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\RemovableFileType;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\FixturesBundle\Entity\Event as EventEntity;
use FSi\FixturesBundle\Form\CommentType;
use FSi\FixturesBundle\Form\FilesType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @Admin\Element
 */
class Event extends TranslatableCRUDElement
{
    public function getId(): string
    {
        return 'admin_event';
    }

    public function getClassName(): string
    {
        return EventEntity::class;
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
        $datagrid = $factory->createDataGrid($this->getId());

        $datagrid->addColumn('name', 'text', [
            'label' => 'admin.events.grid.name',
            'editable' => true,
        ]);

        $datagrid->addColumn('agreement', 'fsi_file', [
            'label' => 'admin.events.grid.agreement'
        ]);

        $datagrid->addColumn('actions', 'action', [
            'label' => 'admin.grid.actions',
            'field_mapping' => ['id'],
            'actions' => [
                'edit' => [
                    'route_name' => 'fsi_admin_translatable_form',
                    'additional_parameters' => ['element' => $this->getId()],
                    'parameters_field_mapping' => ['id' => 'id']
                ],
                'display' => [
                    'route_name' => 'fsi_admin_translatable_display',
                    'additional_parameters' => ['element' => 'admin_event_preview'],
                    'parameters_field_mapping' => ['id' => 'id']
                ],
            ]
        ]);

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
        $qb = $this->getRepository()->createTranslatableQueryBuilder('e', 't', 'dt');

        $datasource = $factory->createDataSource('doctrine-orm', ['qb' => $qb], $this->getId());
        $datasource->addField('name', 'text', 'like', ['field' => 't.name']);

        return $datasource;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        $form = $factory->create(FormType::class, $data ?? new EventEntity(), ['data_class' => $this->getClassName()]);
        $form->add('name', TextType::class, ['label' => 'admin.events.form.name']);
        $form->add('agreement', RemovableFileType::class, ['required' => false]);
        $form->add('description', CKEditorType::class, ['required' => false]);
        $form->add('comments', CollectionType::class, [
            'entry_type' => CommentType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ]);
        $form->add('files', CollectionType::class, [
            'entry_type' => FilesType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ]);

        return $form;
    }
}
