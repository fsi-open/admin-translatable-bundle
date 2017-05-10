<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class Form extends Element
{
    protected $selector = ['css' => 'form'];

    public function findLabel($label)
    {
        return $this->find('css', sprintf('label:contains("%s")', $label));
    }
}
