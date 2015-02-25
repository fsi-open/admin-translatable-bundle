<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\DisplayElement;

abstract class TranslatableDisplayElement extends DisplayElement
{
    public function getRoute()
    {
        return 'fsi_admin_translatable_display';
    }
}
