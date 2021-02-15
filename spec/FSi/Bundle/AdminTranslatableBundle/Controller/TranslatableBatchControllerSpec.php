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
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class TranslatableBatchControllerSpec extends ObjectBehavior
{
    public function let(
        Environment $twig,
        ContextManager $contextManager,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $this->beConstructedWith($twig, $contextManager, $eventDispatcher);
    }

    public function it_should_handle_batch_action(
        BatchElement $element,
        Request $request,
        Response $response,
        ContextManager $contextManager,
        ContextInterface $context
    ): void {
        $contextManager->createContext('fsi_admin_translatable_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }
}
