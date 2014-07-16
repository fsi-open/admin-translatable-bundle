<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class TopMenu extends Element
{
    protected $selector = array('css' => 'div.navbar.navbar-inverse.navbar-fixed-top');

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
        return $this->getTranslatableSwitcher()->has('css', 'li > ul.dropdown-menu');
    }

    public function hasActiveTranslatableLanguage($locale)
    {
        return $this->has('css', sprintf('li#translatable-language ul li.active:contains(%s)', $locale));
    }

    public function findTranslatableLanguageElement($translatableLocale)
    {
        return $this->find('css', sprintf('li#translatable-language ul li a:contains("%s")', $translatableLocale));
    }

    public function clickTranslatableDropdown()
    {
        return $this->find('css', 'li#translatable-language a.dropdown-toggle')->click();
    }
}
