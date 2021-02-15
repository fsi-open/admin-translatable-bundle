<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\CRUDListElementContext as AdminCRUDListElementContext;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;

class CRUDListElementContext extends AdminCRUDListElementContext
{
    protected function getSupportedRoute(): string
    {
        return 'fsi_admin_translatable_list';
    }

    public function supportsElement(Element $element): bool
    {
        if (false === parent::supportsElement($element)) {
            return false;
        }

        return $element instanceof TranslatableCRUDElement;
    }
}
