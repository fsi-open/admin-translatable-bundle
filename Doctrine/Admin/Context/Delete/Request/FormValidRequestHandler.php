<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Delete\Request;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Delete\Request\FormValidRequestHandler as BaseHandler;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class FormValidRequestHandler extends BaseHandler
{
    /**
     * @param AdminEvent $event
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $this->validateEvent($event);
        if ($request->request->has('confirm')) {
            if ($event->getForm()->isValid()) {
                $this->eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_PRE_DELETE, $event);
                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                $entities = $this->getEntities($event->getElement(), $request);
                foreach ($entities as $entity) {
                    $event->getElement()->delete($entity);
                }

                $this->eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_POST_DELETE, $event);
                if ($event->hasResponse()) {
                    return $event->getResponse();
                }
            }

            return new RedirectResponse($this->router->generate(
                'fsi_admin_translatable_crud_list',
                array(
                    'element' => $event->getElement()->getId(),
                    'locale' => $event->getRequest()->get('locale'),
                )
            ));
        }
    }
}
