<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;

/**
 * @Admin\Element
 */
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
        return 'FSi\FixturesBundle\Entity\Resource';
    }
}
