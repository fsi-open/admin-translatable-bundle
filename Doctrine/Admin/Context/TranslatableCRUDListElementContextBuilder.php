<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\CRUDListElementContextBuilder;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;

class TranslatableCRUDListElementContextBuilder extends CRUDListElementContextBuilder
{
    /**
     * @param TranslatableCRUDListElementContext $context
     */
    public function __construct(TranslatableCRUDListElementContext $context)
    {
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_translatable_list';
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
