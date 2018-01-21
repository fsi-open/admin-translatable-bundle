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
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslatableListElementContextSpec extends ObjectBehavior
{
    function let(
        ListElement $element,
        DataSourceInterface $datasource,
        DataGridInterface $datagrid,
        HandlerInterface $handler
    ) {
        $this->beConstructedWith([$handler], 'some_template.html.twig');
        $element->createDataGrid()->willReturn($datagrid);
        $element->createDataSource()->willReturn($datasource);
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf(ContextInterface::class);
    }

    function it_has_array_data()
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('datagrid_view');
        $this->getData()->shouldHaveKeyInArray('datasource_view');
        $this->getData()->shouldHaveKeyInArray('element');
    }

    function it_has_template(ListElement $element)
    {
        $element->hasOption('template_list')->willReturn(true);
        $element->getOption('template_list')->willReturn('this_is_list_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_list_template.html.twig');
    }

    function it_returns_default_template_if_no_option(ListElement $element)
    {
        $element->hasOption('template_list')->willReturn(false);
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('some_template.html.twig');
    }

    function it_handles_request_with_request_handlers(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type(ListEvent::class), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    function it_returns_response_from_handler(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type(ListEvent::class), $request)
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
