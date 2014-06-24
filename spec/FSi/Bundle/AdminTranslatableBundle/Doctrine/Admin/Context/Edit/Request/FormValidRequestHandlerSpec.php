<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Edit\Request;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcher $eventDispatcher, FormEvent $event, Router $router)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form,
        TranslatableCRUDElement $element,
        Router $router
    ) {
        $request->getMethod()->willReturn('POST');

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(CRUDEvents::CRUD_EDIT_ENTITY_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(CRUDEvents::CRUD_EDIT_ENTITY_POST_SAVE, $event)
            ->shouldBeCalled();

        $element->getId()->willReturn(1);
        $event->getRequest()->willReturn($request);
        $request->get('locale')->willReturn('en');

        $router->generate(
            'fsi_admin_translatable_crud_list',
            array(
                'element' => 1,
                'locale' => 'en',
            ))->willReturn('/list/page');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }
}
