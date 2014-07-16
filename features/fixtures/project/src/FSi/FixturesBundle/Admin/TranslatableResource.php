<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableResourceElement;

class TranslatableResource extends TranslatableResourceElement
{
    public function getId()
    {
        return 'translatable_resource';
    }

    public function getKey()
    {
        return 'resources.translatable_resource';
    }

    public function getName()
    {
        return 'admin.translatable_resource.name';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Resource';
    }
}
