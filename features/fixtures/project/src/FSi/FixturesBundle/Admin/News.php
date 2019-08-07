<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\FixturesBundle\Entity\News as NewsEntity;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class News extends CRUDElement
{
    public function getId(): string
    {
        return 'admin_news';
    }

    public function getClassName(): string
    {
        return NewsEntity::class;
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
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

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
        return $factory->createDataSource('doctrine-orm', ['entity' => $this->getClassName()], $this->getId());
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        $form = $factory->create(FormType::class, $data, [
            'data_class' => $this->getClassName(),
        ]);

        $form->add('title', TextareaType::class, ['label' => 'admin.news.form.title']);

        return $form;
    }
}
