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
use FSi\Bundle\AdminBundle\Doctrine\Admin\DisplayElement;
use PhpSpec\ObjectBehavior;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslatableDisplayControllerSpec extends ObjectBehavior
{
    function let(
        EngineInterface $templating,
        ContextManager $contextManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->beConstructedWith($templating, $contextManager, $eventDispatcher);
    }

    function it_should_handle_display_action(
        DisplayElement $element,
        Request $request,
        Response $response,
        ContextManager $contextManager,
        ContextInterface $context,
        EngineInterface $templating
    ) {
        $contextManager->createContext('fsi_admin_translatable_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('translatable_template');
        $context->getData()->willReturn([1, 2, 3]);

        $templating->renderResponse('translatable_template', [1, 2, 3])->willReturn($response);

        $this->displayAction($element, $request)->shouldReturn($response);
    }
}
