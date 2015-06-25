<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\ListElementContextBuilder;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableListElement;

class TranslatableListElementContextBuilder extends ListElementContextBuilder
{
    public function __construct(TranslatableListElementContext $context)
    {
        parent::__construct($context);
    }

    public function supports($route, Element $element)
    {
        if (!parent::supports($route, $element)) {
            return false;
        }

        return $element instanceof TranslatableListElement;
    }
}