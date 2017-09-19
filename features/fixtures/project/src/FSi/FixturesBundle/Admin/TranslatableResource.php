<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableResourceElement;

/**
 * @Admin\Element
 */
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
        return 'FSi\FixturesBundle\Entity\Resource';
    }
}
