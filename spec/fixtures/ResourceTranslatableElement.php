<?php

namespace FSi\Bundle\AdminTranslatableBundle\spec\fixtures;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableResourceElement;

class ResourceTranslatableElement extends TranslatableResourceElement
{
    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'translatable_element';
    }

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return 'resources.translatable_element';
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName(): string
    {
        return 'FSi\Bundle\DemoBundle\Entity\Resource';
    }
}
