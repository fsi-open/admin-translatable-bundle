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
use FSi\Bundle\AdminBundle\Doctrine\Admin\FormElement;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class TranslatableFormControllerSpec extends ObjectBehavior
{
    public function let(
        Environment $twig,
        ContextManager $contextManager,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $this->beConstructedWith($twig, $contextManager, $eventDispatcher);
    }

    public function it_should_handle_form_action(
        FormElement $element,
        Request $request,
        Response $response,
        ContextManager $contextManager,
        ContextInterface $context,
        Environment $twig
    ): void {
        $contextManager->createContext('fsi_admin_translatable_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('translatable_template');
        $context->getData()->willReturn([1, 2, 3]);

        $twig->render('translatable_template', [1, 2, 3])->willReturn('response');

        $this->formAction($element, $request)->getContent()->shouldReturn('response');
    }
}
