<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

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

        $form->add('title', 'text', array('label' => 'admin.news.title'));

        return $form;
    }
}
