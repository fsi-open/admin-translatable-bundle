<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminTranslatableBundle\Form\TypeSolver;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @Admin\Element
 */
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

        $datagrid->addColumn('title', 'text', [
            'label' => 'admin.news.grid.title'
        ]);

        $datagrid->addColumn('actions', 'action', [
            'label' => 'admin.grid.actions',
            'field_mapping' => ['id'],
            'actions' => [
                'edit' => [
                    'route_name' => 'fsi_admin_crud_edit',
                    'additional_parameters' => ['element' => $this->getId()],
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
        return $factory->createDataSource('doctrine', ['entity' => $this->getClassName()], $this->getId());
    }

    /**
     * {@inheritDoc}
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $formType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form');
        $textareaType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\TextareaType', 'textarea');

        $form = $factory->create($formType, $data, [
            'data_class' => $this->getClassName(),
        ]);

        $form->add('title', $textareaType, ['label' => 'admin.news.form.title']);

        return $form;
    }
}
