<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Admin\CRUD\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class TranslatableFormElementContextSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\FormElement $element
     * @param \Symfony\Component\Form\Form $form
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     */
    function let($element, $form, $handler)
    {
        $this->beConstructedWith(array($handler), 'some_template.html.twig');
        $element->createForm(null)->willReturn($form);
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    /**
     * @param \Symfony\Component\Form\Form $form
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\FormElement $element
     * @param \FSi\Component\DataIndexer\DataIndexerInterface $dataIndexer
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_have_array_data($form, $element, $dataIndexer, $request)
    {
        $form->createView()->willReturn('form_view');
        $form->getData()->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');

        $form->getData()->willReturn(array('object'));
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getIndex(array('object'))->willReturn('id');
        $this->getData()->shouldHaveKeyInArray('id');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\FormElement $element
     */
    function it_has_template($element)
    {
        $element->hasOption('template_form')->willReturn(true);
        $element->getOption('template_form')->willReturn('this_is_form_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_form_template.html.twig');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\FormElement $element
     */
    function it_return_default_template_if_no_option($element)
    {
        $element->hasOption('template_form')->willReturn(false);
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('some_template.html.twig');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_handle_request_with_request_handlers($handler, $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_return_response_from_handler($handler, $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->willReturn(new Response());

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function getMatchers()
    {
        return array(
            'haveKeyInArray' => function($subject, $key) {
                if (!is_array($subject)) {
                    return false;
                }

                return array_key_exists($key, $subject);
            },
        );
    }
}
