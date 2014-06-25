<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class Grid extends Element
{
    protected $selector = array('css' => 'table.table.table-hover.table-striped.table-bordered');

    public function hasColumn($columnName)
    {
        return $this->has('css', sprintf('th span:contains("%s")', $columnName));
    }
}
