<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\FixturesBundle\Form\CommentType;
use FSi\FixturesBundle\Form\FilesType;
use Symfony\Component\Form\FormFactoryInterface;
use FSi\Bundle\AdminBundle\Annotation as Admin;

/**
 * @Admin\Element
 */
class Event extends TranslatableCRUDElement
{
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return 'admin_event';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'admin.events.type_name';
    }

    /**
     * {@inheritDoc}
     */
    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Event';
    }

    /**
     * {@inheritDoc}
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
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

    /**
     * {@inheritDoc}
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        $qb = $this->getRepository()->createTranslatableQueryBuilder('e', 't', 'dt');

        $datasource = $factory->createDataSource('doctrine', ['qb' => $qb], $this->getId());

        $datasource->addField('name', 'text', 'like', [
            'field' => 't.name'
        ]);

        return $datasource;
    }

    /**
     * {@inheritDoc}
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $form = $factory->create('form', $data, [
            'data_class' => $this->getClassName(),
        ]);

        $form->add('name', 'text', ['label' => 'admin.events.form.name']);

        $form->add('agreement', 'fsi_removable_file', [
            'required' => false
        ]);

        $form->add('description', 'ckeditor', [
            'required' => false
        ]);

        $form->add('comments', 'collection', [
            'type' => new CommentType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ]);

        $form->add('files', 'collection', [
            'type' => new FilesType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ]);

        return $form;
    }
}
