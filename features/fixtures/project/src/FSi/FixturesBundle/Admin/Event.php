<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\FixturesBundle\Form\CommentType;
use Symfony\Component\Form\FormFactoryInterface;

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
            'label' => 'admin.events.grid.name'
        ));

        $datagrid->addColumn('actions', 'action', array(
            'label' => 'admin.grid.actions',
            'field_mapping' => array('id'),
            'actions' => array(
                'edit' => array(
                    'route_name' => 'fsi_admin_translatable_crud_edit',
                    'additional_parameters' => array('element' => $this->getId()),
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
        return $factory->createDataSource('doctrine', array('entity' => $this->getClassName()), $this->getId());
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

        $form->add('comments', 'collection', array(
            'type' => new CommentType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ));

        return $form;
    }
}
