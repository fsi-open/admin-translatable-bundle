<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Resource\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ResourceEvents;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Request\AbstractTranslatableFormValidRequestHandler;

class FormValidRequestHandler extends AbstractTranslatableFormValidRequestHandler
{
    /**
     * @return string
     */
    protected function getEntityPreSaveEventName()
    {
        return ResourceEvents::RESOURCE_PRE_SAVE;
    }

    /**
     * @return string
     */
    protected function getEntityPostSaveEventName()
    {
        return ResourceEvents::RESOURCE_POST_SAVE;
    }

    /**
     * @return string
     */
    protected function getResponsePreRenderEventName()
    {
        return ResourceEvents::RESOURCE_RESPONSE_PRE_RENDER;
    }

    /**
     * @param AdminEvent $event
     */
    protected function action(AdminEvent $event)
    {
        if ($event instanceof FormEvent) {
            $data = $event->getForm()->getData();
            foreach ($data as $object) {
                $event->getElement()->getObjectManager()->persist($object);
            }

            $event->getElement()->getObjectManager()->flush();
        }
    }

    /**
     * @return string
     */
    protected function getSuccessRouteName()
    {
        return 'fsi_admin_translatable_resource';
    }
}
