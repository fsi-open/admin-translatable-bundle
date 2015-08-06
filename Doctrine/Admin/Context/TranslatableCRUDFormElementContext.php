<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\CRUDFormElementContext;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;

class TranslatableCRUDFormElementContext extends CRUDFormElementContext
{
    /**
     * {@inheritdoc}
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_translatable_form';
    }

    /**
     * {@inheritdoc}
     */
    public function supportsElement(Element $element)
    {
        if (!parent::supportsElement($element)) {
            return false;
        }

        return $element instanceof TranslatableCRUDElement;
    }
}
