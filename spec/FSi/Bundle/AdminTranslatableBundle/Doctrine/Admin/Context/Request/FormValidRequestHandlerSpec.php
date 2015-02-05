<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $eventDispatcher, RouterInterface $router)
    {
        $this->beConstructedWith($eventDispatcher, $router);
    }

    function it_handle_request(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form,
        TranslatableCRUDElement $element,
        RouterInterface $router,
        ParameterBag $query
    ) {
        //$request->getMethod()->willReturn('POST');
        $request->isMethod("POST")->willReturn(true);
        $request->query = $query;
        $query->has('redirect_uri')->willReturn(false);

        $event->hasResponse()->willReturn(false);
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->shouldBeCalled();

        $element->getId()->willReturn(1);
        $element->getSuccessRoute()->willReturn('success_route');
        $element->getSuccessRouteParameters()->willReturn(array('element' => 1, 'locale' => 'en',));
        $event->getRequest()->willReturn($request);
        $request->get('locale')->willReturn('en');

        $router->generate(
            'success_route',
            array(
                'element' => 1,
                'locale' => 'en',
            ))->willReturn('/list/page');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }
}
