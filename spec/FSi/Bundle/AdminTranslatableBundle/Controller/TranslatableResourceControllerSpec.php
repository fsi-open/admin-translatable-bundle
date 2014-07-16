<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Read\Context;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslatableResourceControllerSpec extends ObjectBehavior
{
    function let(ContextManager $manager, DelegatingEngine $templating)
    {
        $this->beConstructedWith($templating, $manager, 'default_resource');
    }

    function it_return_response_from_context_in_resource_action(
        ContextManager $manager,
        AbstractResource $element,
        Context $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_translatable_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->resourceAction($element, $request)->shouldReturn($response);
    }
}
