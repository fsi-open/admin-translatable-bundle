<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Delete\Request;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $eventDispatcher, FormEvent $event, Router $router)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    function it_handles_request_if_confirmed(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterBag,
        EventDispatcher $eventDispatcher,
        Form $form,
        TranslatableCRUDElement $element,
        DataIndexerInterface $dataIndexer,
        Router $router
    ) {
        $request->request = $requestParameterBag;
        $requestParameterBag->has('confirm')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_PRE_DELETE, $event)
            ->shouldBeCalled();

        $requestParameterBag->get('indexes', array())->willReturn(array(1, 2, 3));

        $event->getElement()->willReturn($element);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getData(1)->shouldBeCalled()->willReturn(new \stdClass());
        $dataIndexer->getData(2)->shouldBeCalled()->willReturn(new \stdClass());
        $dataIndexer->getData(3)->shouldBeCalled()->willReturn(new \stdClass());
        $element->delete(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_POST_DELETE, $event)
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
