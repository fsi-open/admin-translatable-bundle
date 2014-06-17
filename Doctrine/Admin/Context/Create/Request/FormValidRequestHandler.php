<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Create\Request;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Request\AbstractTranslatableFormValidRequestHandler;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;

class FormValidRequestHandler extends AbstractTranslatableFormValidRequestHandler
{
    /**
     * @return string
     */
    protected function getEntityPreSaveEventName()
    {
        return CRUDEvents::CRUD_CREATE_ENTITY_PRE_SAVE;
    }

    /**
     * @return string
     */
    protected function getEntityPostSaveEventName()
    {
        return CRUDEvents::CRUD_CREATE_ENTITY_POST_SAVE;
    }

    /**
     * @return string
     */
    protected function getResponsePreRenderEventName()
    {
        return CRUDEvents::CRUD_CREATE_RESPONSE_PRE_RENDER;
    }

    /**
     * @return string
     */
    protected function getSuccessRouteName()
    {
        return 'fsi_admin_translatable_crud_list';
    }
}
