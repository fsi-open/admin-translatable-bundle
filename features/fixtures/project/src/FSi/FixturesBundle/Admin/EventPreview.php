<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Display\PropertyAccessDisplay;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableDisplayElement;
use FSi\FixturesBundle\Entity\Event;

class EventPreview extends TranslatableDisplayElement
{
    public function getId(): string
    {
        return 'admin_event_preview';
    }

    public function getClassName(): string
    {
        return Event::class;
    }

    protected function initDisplay($object): Display
    {
        $objectDisplay = new PropertyAccessDisplay($object);
        $objectDisplay->add('name', 'Name');

        return $objectDisplay;
    }
}
