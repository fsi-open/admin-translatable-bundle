<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Resource\Request;

use Doctrine\Common\Persistence\ObjectManager;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableResourceElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ResourceEvents;
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
        TranslatableResourceElement $element,
        Router $router,
        ObjectManager $objectManager
    ) {
        $request->getMethod()->willReturn('POST');
        $event->getRequest()->willReturn($request);
        $request->get('locale')->willReturn('en');

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(ResourceEvents::RESOURCE_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(array(new \stdClass(), new \stdClass()));
        $event->getElement()->willReturn($element);
        $element->getObjectManager()->willReturn($objectManager);
        $objectManager->persist(Argument::type('stdClass'))->shouldBeCalledTimes(2);
        $objectManager->flush()->shouldBeCalled();

        $eventDispatcher->dispatch(ResourceEvents::RESOURCE_POST_SAVE, $event)
            ->shouldBeCalled();

        $element->getId()->willReturn('test-resource');
        $router->generate('fsi_admin_translatable_resource', array('element' => 'test-resource', 'locale' => 'en'))
            ->willReturn('/resource/test-resource');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }
}
