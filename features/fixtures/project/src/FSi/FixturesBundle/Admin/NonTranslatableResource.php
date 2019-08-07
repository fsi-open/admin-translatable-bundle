<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\FixturesBundle\Entity\Resource;

class NonTranslatableResource extends ResourceElement
{
    public function getId(): string
    {
        return 'non_translatable_resource';
    }

    public function getKey(): string
    {
        return 'resources.non_translatable_resource';
    }

    public function getClassName(): string
    {
        return Resource::class;
    }
}
