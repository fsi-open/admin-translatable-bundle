<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\EventListener;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListenerSpec extends ObjectBehavior
{
    public function let(LocaleManager $localeManager): void
    {
        $this->beConstructedWith($localeManager);
    }

    public function it_implements_Event_Subscriber_Interface(): void
    {
        $this->beAnInstanceOf(EventSubscriberInterface::class);
    }

    public function it_subscribes_kernel_request_event(): void
    {
        $this->getSubscribedEvents()->shouldReturn([KernelEvents::REQUEST => 'onKernelRequest']);
    }

    public function it_does_nothing_if_request_is_not_master(
        GetResponseEvent $event,
        Request $request,
        LocaleManager $localeManager): void
    {
        $event->getRequest()->willReturn($request);
        $request->get('locale')->willReturn(null);

        $localeManager->setLocale(Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }

    public function it_sets_locale_to_locale_manager(
        GetResponseEvent $event,
        Request $request,
        LocaleManager $localeManager
    ): void {
        $event->getRequest()->willReturn($request);
        $request->get('locale')->willReturn('pl');
        $localeManager->setLocale('pl')->shouldBeCalled();

        $this->onKernelRequest($event);
    }
}
