<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Request\AbstractFormValidRequestHandler as BaseHandler;

abstract class AbstractTranslatableFormValidRequestHandler extends BaseHandler
{
    /**
     * @param AdminEvent $event
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response|RedirectResponse
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $this->validateEvent($event);
        if ($request->getMethod() == 'POST') {
            if ($event->getForm()->isValid()) {
                $this->eventDispatcher->dispatch($this->getEntityPreSaveEventName(), $event);

                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                $this->action($event);
                $this->eventDispatcher->dispatch($this->getEntityPostSaveEventName(), $event);

                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                return new RedirectResponse(
                    $this->router->generate(
                        $this->getSuccessRouteName(),
                        array(
                            'element' => $event->getElement()->getId(),
                            'locale' => $event->getRequest()->get('locale'),
                        )
                    )
                );
            }
        }

        $this->eventDispatcher->dispatch($this->getResponsePreRenderEventName(), $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }
    }
}
