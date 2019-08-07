<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class TranslatableResourceControllerSpec extends ObjectBehavior
{
    function let(
        Environment $twig,
        ContextManager $contextManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->beConstructedWith($templating, $contextManager, $eventDispatcher);
    }

    function it_should_handle_resource_action(
        ResourceElement $element,
        Request $request,
        ContextManager $contextManager,
        ContextInterface $context,
        Environment $twig
    ) {
        $contextManager->createContext('fsi_admin_translatable_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('translatable_template');
        $context->getData()->willReturn([1, 2, 3]);

        $twig->render('translatable_template', [1, 2, 3])->willReturn('response');

        $this->resourceAction($element, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
