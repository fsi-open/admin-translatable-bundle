<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element;

use Behat\Behat\Exception\BehaviorException;
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
        return $this->has('css', sprintf('#translatable-switcher ul li.active:contains(%s)', $locale));
    }

    public function findTranslatableLanguageElement($translatableLocale)
    {
        $selector = sprintf('#translatable-switcher ul li a:contains("%s")', $translatableLocale);
        $element = $this->find('css', $selector);

        if (null === $element) {
            throw new BehaviorException(sprintf('Unable to find %s', $selector));
        }

        return $element;
    }

    public function clickTranslatableDropdown()
    {
        $this->find('css', '#translatable-switcher a.dropdown-toggle')->click();
    }
}
