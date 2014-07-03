<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

class EventsList extends Page
{
    protected $path = '/admin/{locale}/admin_events/list';

    public function findTranslatableLanguageElement($translatableLocale)
    {
        return $this->find('css', sprintf('li#translatable-language ul li a:contains("%s")', $translatableLocale));
    }

    public function clickTranslatableDropdown()
    {
        return $this->find('css', 'li#translatable-language a.dropdown-toggle')->click();
    }

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
