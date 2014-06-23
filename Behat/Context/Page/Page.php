<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page as BasePage;

class Page extends BasePage
{
    public function isOpen(array $urlParameters = array())
    {
        $this->verifyPage();

        return true;
    }
}
