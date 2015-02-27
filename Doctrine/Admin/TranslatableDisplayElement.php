<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\DisplayElement;

abstract class TranslatableDisplayElement extends DisplayElement implements TranslatableAwareElement
{
    use TranslatableAwareElementImpl;

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_translatable_display';
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteParameters()
    {
        return $this->appendLocaleParameter(parent::getRouteParameters());
    }
}
