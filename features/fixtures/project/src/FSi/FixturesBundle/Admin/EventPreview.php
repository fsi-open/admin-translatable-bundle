<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Display\PropertyAccessDisplay;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableDisplayElement;

/**
 * @Admin\Element
 */
class EventPreview extends TranslatableDisplayElement
{
    public function getId(): string
    {
        return 'admin_event_preview';
    }

    public function getClassName(): string
    {
        return 'FSi\FixturesBundle\Entity\Event';
    }

    protected function initDisplay($object): Display
    {
        $objectDisplay = new PropertyAccessDisplay($object);
        $objectDisplay->add('name', 'Name');

        return $objectDisplay;
    }
}
