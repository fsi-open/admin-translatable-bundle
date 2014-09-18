<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

class EventsList extends Page
{
    protected $path = '/admin/{locale}/admin_event/list';

    public function pressBatchCheckboxInRow()
    {
        $this->find('css', 'table > tbody > tr input[type="checkbox"]')->check();
    }

    public function selectBatchAction($action)
    {
        $this->find('css', 'select#batch_action')->selectOption($action);
        $this->find('css', '#batch_action_confirmation')->click();
    }
}
