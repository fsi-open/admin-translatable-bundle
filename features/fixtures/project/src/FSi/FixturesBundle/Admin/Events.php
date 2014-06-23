<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class Events extends TranslatableCRUDElement
{
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return 'admin_events';
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
        return 'FSi\FixturesBundle\Entity\Events';
    }

    /**
     * {@inheritDoc}
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        return $factory->createDataGrid($this->getId());
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

        $form->add('name', 'text', array('label' => 'admin.events.form.name.label'));

        return $form;
    }
}
