<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page as BasePage;

class Page extends BasePage
{
    public function getTitle()
    {
        return $this->find('css', 'h3#page-header')->getText();
    }

    public function getMenu()
    {
        return $this->find('css', '#top-menu');
    }
}
