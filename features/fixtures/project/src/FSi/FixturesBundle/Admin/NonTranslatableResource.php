<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;

/**
 * @Admin\Element
 */
class NonTranslatableResource extends ResourceElement
{
    public function getId()
    {
        return 'non_translatable_resource';
    }

    public function getKey()
    {
        return 'resources.non_translatable_resource';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Resource';
    }
}
