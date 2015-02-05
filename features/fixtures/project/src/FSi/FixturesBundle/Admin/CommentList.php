<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Bundle\AdminBundle\Annotation as Admin;

/**
 * @Admin\Element
 */
class CommentList extends TranslatableListElement
{
    public function getId()
    {
        return 'admin_comment';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Comment';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        $datagrid = $factory->createDataGrid($this->getId());

        $datagrid->addColumn('text', 'text', array(
            'label' => 'admin.comment.grid.text'
        ));

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        $qb = $this->getRepository()->createTranslatableQueryBuilder('e', 't', 'dt');

        return $factory->createDataSource('doctrine', array('qb' => $qb), $this->getId());
    }
}
