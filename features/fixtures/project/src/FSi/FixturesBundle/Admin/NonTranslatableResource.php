<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;

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

    public function getName()
    {
        return 'admin.non_translatable_resource.name';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Resource';
    }
}
