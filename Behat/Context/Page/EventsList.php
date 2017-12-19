<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class EventsList extends Page
{
    protected $path = '/admin/{locale}/list/admin_event';

    public function pressBatchCheckboxInRow(): void
    {
        $this->find('css', 'table > tbody > tr input[type="checkbox"]')->check();
    }

    public function selectBatchAction($action): void
    {
        $this->find('css', '.datagrid-actions select')->selectOption($action);
    }

    public function open(array $urlParameters = ['locale' => 'en']): Page
    {
        return parent::open($urlParameters);
    }
}
