<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Doctrine\Admin\FormElement;
use PhpSpec\ObjectBehavior;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslatableFormControllerSpec extends ObjectBehavior
{
    function let(EngineInterface $templating, ContextManager $contextManager)
    {
        $this->beConstructedWith($templating, $contextManager, '@FSiAdmin/Form/form.html.twig');
    }

    function it_should_handle_form_action(
        FormElement $element,
        Request $request,
        Response $response,
        ContextManager $contextManager,
        ContextInterface $context,
        EngineInterface $templating
    ) {
        $contextManager->createContext('fsi_admin_translatable_form', $element)->willReturn($context);
        $context->handleRequest($request)->shouldBeCalled();
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn([1, 2, 3]);

        $templating->renderResponse('@FSiAdmin/Form/form.html.twig', [1, 2, 3])->willReturn($response);

        $this->formAction($element, $request)->shouldReturn($response);
    }
}
