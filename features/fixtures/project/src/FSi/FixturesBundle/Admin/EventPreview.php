<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Display\ObjectDisplay;
use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableDisplayElement;

/**
 * @Admin\Element
 */
class EventPreview extends TranslatableDisplayElement
{
    public function getId()
    {
        return 'admin_event_preview';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Event';
    }

    protected function initDisplay($object)
    {
        $objectDisplay = new ObjectDisplay($object);
        $objectDisplay->add('name');

        return $objectDisplay;
    }
}
