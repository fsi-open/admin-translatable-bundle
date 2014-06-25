<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

class EventsList extends Page
{
    protected $path = '/admin/{locale}/admin_events/list';

    public function getTranslatableLanguageDropdown()
    {
        return $this->find('css', 'li#translatable-language');
    }
}
