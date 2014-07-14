<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Resource;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Resource\ContextBuilder as BaseContextBuilder;

class ContextBuilder extends BaseContextBuilder
{
    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_translatable_resource';
    }
}
