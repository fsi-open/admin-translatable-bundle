<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

use Behat\Behat\Exception\BehaviorException;

class NonTranslatableResourceEdit extends Page
{
    protected $path = '/admin/resource/non_translatable_resource';

    public function getHeader()
    {
        return $this->find('css', '#page-header')->getText();
    }

    protected function verifyPage()
    {
        if (!$this->has('css', '#page-header:contains("Edit resources")')) {
            throw new BehaviorException(sprintf('%s page is missing "Edit resources" header', $this->path));
        }
    }
}
