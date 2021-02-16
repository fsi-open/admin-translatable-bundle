<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslatableFormElementContextSpec extends ObjectBehavior
{
    public function let(FormElement$element, FormInterface $form, HandlerInterface $handler): void
    {
        $this->beConstructedWith([$handler], 'some_template.html.twig');
        $element->createForm(null)->willReturn($form);
        $this->setElement($element);
    }

    public function it_is_context(): void
    {
        $this->shouldBeAnInstanceOf(ContextInterface::class);
    }

    public function it_have_array_data(
        FormInterface $form,
        FormElement $element,
        DataIndexerInterface $dataIndexer,
        Request $request
    ): void {
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

    public function it_has_template(FormElement$element): void
    {
        $element->hasOption('template_form')->willReturn(true);
        $element->getOption('template_form')->willReturn('this_is_form_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_form_template.html.twig');
    }

    public function it_return_default_template_if_no_option(FormElement $element): void
    {
        $element->hasOption('template_form')->willReturn(false);
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('some_template.html.twig');
    }

    public function it_handle_request_with_request_handlers(HandlerInterface $handler, Request $request): void
    {
        $handler->handleRequest(Argument::type(FormEvent::class), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    public function it_return_response_from_handler(HandlerInterface $handler, Request $request): void
    {
        $handler->handleRequest(Argument::type(FormEvent::class), $request)
            ->willReturn(new Response());

        $this->handleRequest($request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function getMatchers(): array
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
