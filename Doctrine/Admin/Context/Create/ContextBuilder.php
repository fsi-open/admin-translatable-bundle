<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Create;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Create\ContextBuilder as BaseContext;

class ContextBuilder extends BaseContext
{
    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_translatable_crud_create';
    }
}
