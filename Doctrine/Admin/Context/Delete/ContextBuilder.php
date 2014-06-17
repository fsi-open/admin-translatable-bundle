<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Delete;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Delete\ContextBuilder as BaseContext;

class ContextBuilder extends BaseContext
{
    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_translatable_crud_delete';
    }
}
