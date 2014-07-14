<?php

namespace FSi\Bundle\AdminTranslatableBundle\spec\fixtures;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableResourceElement;

class ResourceTranslatableElement extends TranslatableResourceElement
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'translatable_element';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin.translatable_element';
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'resources.translatable_element';
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSi\Bundle\DemoBundle\Entity\Resource';
    }
}
