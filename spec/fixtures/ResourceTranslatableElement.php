<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\spec\fixtures;

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
