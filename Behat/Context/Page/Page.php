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

    public function hasFollowingLocales($locale)
    {
        return $this->getTranslatableSwitcher()->has('css', sprintf('li > ul li a:contains(%s)', $locale));
    }

    public function isTranslatableSwitcherActive()
    {
        return $this->getMenu()->has('css', 'li > ul.dropdown-menu');
    }

    public function hasActiveTranslatableLanguage($locale)
    {
        return $this->has('css', sprintf('li#translatable-language ul li.active:contains(%s)', $locale));
    }

    public function getMenu()
    {
        return $this->find('css', '#top-menu');
    }
}
