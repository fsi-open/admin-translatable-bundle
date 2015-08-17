<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class News extends CRUDElement
{
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return 'admin_news';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'admin.news.type_name';
    }

    /**
     * {@inheritDoc}
     */
    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\News';
    }

    /**
     * {@inheritDoc}
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        $datagrid = $factory->createDataGrid($this->getId());

        $datagrid->addColumn('title', 'text', array(
            'label' => 'admin.news.grid.title'
        ));

        $datagrid->addColumn('actions', 'action', array(
            'label' => 'admin.grid.actions',
            'field_mapping' => array('id'),
            'actions' => array(
                'edit' => array(
                    'route_name' => 'fsi_admin_crud_edit',
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

        $form->add('title', 'text', array('label' => 'admin.news.form.title'));

        return $form;
    }
}
