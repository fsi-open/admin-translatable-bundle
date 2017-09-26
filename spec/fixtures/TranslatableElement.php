<?php

namespace FSi\Bundle\AdminTranslatableBundle\spec\fixtures;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class TranslatableElement extends TranslatableCRUDElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName(): string
    {
        return 'FSiTranslatableDemoBundle:Entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'translatable_entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'admin.translatable_entity.name';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
    }
}
