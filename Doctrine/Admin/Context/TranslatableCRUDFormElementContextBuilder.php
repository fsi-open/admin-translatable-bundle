<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\CRUDFormElementContextBuilder;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;

class TranslatableCRUDFormElementContextBuilder extends CRUDFormElementContextBuilder
{
    /**
     * @param TranslatableCRUDFormElementContext $context
     */
    public function __construct(TranslatableCRUDFormElementContext $context)
    {
        parent::__construct($context);
    }

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
    public function supports($route, Element $element)
    {
        if (!parent::supports($route, $element)) {
            return false;
        }

        return $element instanceof TranslatableCRUDElement;
    }
}
