<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableResourceElement;
use FSi\FixturesBundle\Entity\Resource;

class TranslatableResource extends TranslatableResourceElement
{
    public function getId(): string
    {
        return 'translatable_resource';
    }

    public function getKey(): string
    {
        return 'resources.translatable_resource';
    }

    public function getClassName(): string
    {
        return Resource::class;
    }
}
