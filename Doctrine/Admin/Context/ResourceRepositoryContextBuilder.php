<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContextBuilder as BaseResourceRepositoryContextBuilder;

class ResourceRepositoryContextBuilder extends BaseResourceRepositoryContextBuilder
{
    protected function getSupportedRoute()
    {
        return 'fsi_admin_translatable_resource';
    }
}
