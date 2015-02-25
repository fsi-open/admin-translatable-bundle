<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class Display extends Element
{
    protected $selector = array('css' => 'table');

    public function getRowValue($name)
    {
        return $this->find('css', sprintf('td:contains("%s")+td', $name));
    }
}
