<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridInterface;

abstract class TranslatableCRUDElement extends CRUDElement implements TranslatableAwareElement
{
    use TranslatableAwareElementImpl;

    public function getRoute(): string
    {
        return 'fsi_admin_translatable_list';
    }

    public function getRouteParameters(): array
    {
        return $this->appendLocaleParameter(parent::getRouteParameters());
    }

    public function getSuccessRouteParameters(): array
    {
        return $this->appendLocaleParameter(parent::getSuccessRouteParameters());
    }

    public function createDataGrid(): DataGridInterface
    {
        $datagrid = $this->initDataGrid($this->datagridFactory);

        if (true === $this->getOption('allow_delete') && false === $datagrid->hasColumnType('batch')) {
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

        return $datagrid;
    }
}
