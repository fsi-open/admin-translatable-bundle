<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page as BasePage;

class Page extends BasePage
{
    public function getTitle()
    {
        return $this->find('css', 'h3#page-header')->getText();
    }

    public function getTranslatableSwitcher()
    {
        return $this->find('css', '#translatable-switcher');
    }

    public function hasTranslatableSwitcher()
    {
        return $this->has('css', '#translatable-switcher');
    }

    public function getNumberOfLanguageOptions()
    {
        return count($this->getTranslatableSwitcher()->findAll('css', 'li > ul li'));
    }

    public function isTranslatableSwitcherActive()
    {
        return $this->getMenu()->has('css', 'li > ul.dropdown-menu');
    }

    public function getMenu()
    {
        return $this->find('css', '#top-menu');
    }
}
