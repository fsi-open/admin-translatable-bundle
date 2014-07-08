<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

use Behat\Behat\Exception\BehaviorException;

class EventsDeleteConfirmation extends Page
{
    protected $path = '/admin/{locale}/admin_events/delete';

    public function getConfirmationMessage()
    {
        return $this->find('css', 'div#delete-wrapper > div > p')->getText();
    }

    protected function verifyPage()
    {
        if (!$this->has('css', 'div#delete-wrapper')) {
            throw new BehaviorException(sprintf("Page is not a delete confirmation page"));
        }
    }
}
