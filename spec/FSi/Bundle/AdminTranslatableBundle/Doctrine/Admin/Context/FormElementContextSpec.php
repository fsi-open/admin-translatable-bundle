<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslatableFormElementContextSpec extends ObjectBehavior
{
    function let(FormElement$element, FormInterface $form, HandlerInterface $handler)
    {
        $this->beConstructedWith([$handler], 'some_template.html.twig');
        $element->createForm(null)->willReturn($form);
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_array_data(
        FormInterface $form,
        FormElement $element,
        DataIndexerInterface $dataIndexer,
        Request $request
    ) {
        $form->createView()->willReturn('form_view');
        $form->getData()->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');

        $form->getData()->willReturn(['object']);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getIndex(['object'])->willReturn('id');
        $this->getData()->shouldHaveKeyInArray('id');
    }

    function it_has_template(FormElement$element)
    {
        $element->hasOption('template_form')->willReturn(true);
        $element->getOption('template_form')->willReturn('this_is_form_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_form_template.html.twig');
    }

    function it_return_default_template_if_no_option(FormElement $element)
    {
        $element->hasOption('template_form')->willReturn(false);
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('some_template.html.twig');
    }

    function it_handle_request_with_request_handlers(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    function it_return_response_from_handler(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->willReturn(new Response());

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function getMatchers()
    {
        return [
            'haveKeyInArray' => function($subject, $key) {
                if (!is_array($subject)) {
                    return false;
                }

                return array_key_exists($key, $subject);
            },
        ];
    }
}
