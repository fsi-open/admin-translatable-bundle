<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class Popover extends Element
{
    protected $selector = array('css' => '.popover');

    public function getForm()
    {
        return $this->find('css', 'form');
    }
}
