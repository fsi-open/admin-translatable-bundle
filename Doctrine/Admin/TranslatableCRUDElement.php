<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridInterface;

abstract class TranslatableCRUDElement extends CRUDElement implements TranslatableAwareElement
{
    use TranslatableAwareElementImpl;

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_translatable_list';
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteParameters()
    {
        return $this->appendLocaleParameter(parent::getRouteParameters());
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRouteParameters()
    {
        return $this->appendLocaleParameter(parent::getSuccessRouteParameters());
    }

    /**
     * {@inheritdoc}
     */
    public function createDataGrid()
    {
        $datagrid = $this->initDataGrid($this->datagridFactory);
        if (!is_object($datagrid) || !$datagrid instanceof DataGridInterface) {
            throw new RuntimeException(
                'initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface'
            );
        }

        if ($this->getOption('allow_delete')) {
            if (!$datagrid->hasColumnType('batch')) {
                $datagrid->addColumn('batch', 'batch', [
                    'actions' => [
                        'delete' => [
                            'route_name' => 'fsi_admin_translatable_batch',
                            'additional_parameters' => [
                                'element' => $this->getId(),
                                'locale' => $this->localeManager->getLocale()
                            ],
                            'label' => 'crud.list.batch.delete'
                        ]
                    ],
                    'display_order' => -1000
                ]);
            }
        }

        return $datagrid;
    }
}
