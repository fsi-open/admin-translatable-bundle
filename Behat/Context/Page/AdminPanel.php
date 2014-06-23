<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

class AdminPanel extends Page
{
    protected $path = '/admin';

    public function getMenu()
    {
        return $this->find('css', '#translatable-switcher');
    }

    public function hasMenu()
    {
        return $this->has('css', '#translatable-switcher');
    }

    public function getNumberOfLanguageOptions()
    {
        return count($this->getMenu()->findAll('css', 'li > ul li'));
    }

    public function isTranslatableSwitcherActive()
    {
        return $this->getMenu()->has('css', 'li > ul.dropdown-menu');
    }
}
