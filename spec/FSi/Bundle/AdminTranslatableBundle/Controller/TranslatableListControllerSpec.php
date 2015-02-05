<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Doctrine\Admin\ListElement;
use PhpSpec\ObjectBehavior;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslatableListControllerSpec extends ObjectBehavior
{
    function let(EngineInterface $templating, ContextManager $contextManager)
    {
        $this->beConstructedWith($templating, $contextManager, '@FSiAdmin/List/list.html.twig');
    }

    function it_should_handle_list_action(
        ListElement $element,
        Request $request,
        Response $response,
        ContextManager $contextManager,
        ContextInterface $context,
        EngineInterface $templating
    ) {
        $contextManager->createContext('fsi_admin_translatable_list', $element)->willReturn($context);
        $context->handleRequest($request)->shouldBeCalled();
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array(1, 2, 3));

        $templating->renderResponse('@FSiAdmin/List/list.html.twig', array(1, 2, 3))->willReturn($response);

        $this->listAction($element, $request)->shouldReturn($response);
    }
}
