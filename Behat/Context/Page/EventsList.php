<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

class EventsList extends Page
{
    protected $path = '/admin/{locale}/list/admin_event';

    public function pressBatchCheckboxInRow()
    {
        $this->find('css', 'table > tbody > tr input[type="checkbox"]')->check();
    }

    public function selectBatchAction($action)
    {
        $this->find('css', '.datagrid-actions select')->selectOption($action);
    }
}
