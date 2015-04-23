<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\FixturesBundle\Form\CommentType;
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

        $datagrid->addColumn('name', 'text', array(
            'label' => 'admin.events.grid.name',
            'editable' => true,
        ));

        $datagrid->addColumn('agreement', 'fsi_file', array(
            'label' => 'admin.events.grid.agreement'
        ));

        $datagrid->addColumn('actions', 'action', array(
            'label' => 'admin.grid.actions',
            'field_mapping' => array('id'),
            'actions' => array(
                'edit' => array(
                    'route_name' => 'fsi_admin_translatable_form',
                    'additional_parameters' => array('element' => $this->getId()),
                    'parameters_field_mapping' => array('id' => 'id')
                ),
                'display' => array(
                    'route_name' => 'fsi_admin_translatable_display',
                    'additional_parameters' => array('element' => 'admin_event_preview'),
                    'parameters_field_mapping' => array('id' => 'id')
                ),
            )
        ));

        return $datagrid;
    }

    /**
     * {@inheritDoc}
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        $qb = $this->getRepository()->createTranslatableQueryBuilder('e', 't', 'dt');

        $datasource = $factory->createDataSource('doctrine', array('qb' => $qb), $this->getId());

        $datasource->addField('name', 'text', 'like', array(
            'field' => 't.name'
        ));

        return $datasource;
    }

    /**
     * {@inheritDoc}
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $form = $factory->create('form', $data, array(
            'data_class' => $this->getClassName(),
        ));

        $form->add('name', 'text', array('label' => 'admin.events.form.name'));

        $form->add('agreement', 'fsi_removable_file', array(
            'required' => false
        ));

        $form->add('description', 'ckeditor', array(
            'required' => false
        ));

        $form->add('comments', 'collection', array(
            'type' => new CommentType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ));

        return $form;
    }
}
