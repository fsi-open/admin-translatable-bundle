<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Read;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Read\ContextBuilder as BaseContext;

class ContextBuilder extends BaseContext
{
    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_translatable_crud_list';
    }
}
