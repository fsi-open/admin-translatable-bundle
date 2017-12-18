<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;

/**
 * @Admin\Element
 */
class CommentList extends TranslatableListElement
{
    public function getId(): string
    {
        return 'admin_comment';
    }

    public function getClassName(): string
    {
        return 'FSi\FixturesBundle\Entity\Comment';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
        $datagrid = $factory->createDataGrid($this->getId());

        $datagrid->addColumn('text', 'text', [
            'label' => 'admin.comment.grid.text'
        ]);

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
        $qb = $this->getRepository()->createTranslatableQueryBuilder('e', 't', 'dt');

        return $factory->createDataSource('doctrine-orm', ['qb' => $qb], $this->getId());
    }
}
