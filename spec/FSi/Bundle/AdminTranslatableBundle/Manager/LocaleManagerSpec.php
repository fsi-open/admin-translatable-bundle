<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Manager;

use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Session\Session;

class LocaleManagerSpec extends ObjectBehavior
{
    function let(
        ManagerRegistry $managerRegistry,
        EntityManager $entityManager,
        EventManager $eventManager,
        TranslatableListener $translatableListener,
        Session $session
    ) {
        $managerRegistry->getManager()->willReturn($entityManager);
        $entityManager->getEventManager()->willReturn($eventManager);
        $eventManager->getListeners()
            ->willReturn([
                'preFlush' => [
                    $translatableListener
                ]
            ]);

        $this->beConstructedWith($managerRegistry, $session, ['en', 'de']);
    }

    function it_returns_configured_locales()
    {
        $this->getLocales()->shouldReturn(['en', 'de']);
    }

    function it_sets_locale(
        Session $session,
        TranslatableListener $translatableListener
    ) {

        $session->set('admin-locale', 'pl')->shouldBeCalled();
        $translatableListener->setLocale('pl')->shouldBeCalled();

        $this->setLocale('pl');
    }

    function it_gets_default_locale_when_session_is_empty(
        Session $session,
        TranslatableListener $translatableListener
    ) {
        $session->has('admin-locale')->willReturn(false);
        $translatableListener->getDefaultLocale()->willReturn('en');

        $this->getLocale()->shouldReturn('en');
    }

    function it_gets_locale_when_session_is_not_empty(
        Session $session
    ) {
        $session->has('admin-locale')->willReturn(true);
        $session->get('admin-locale')->willReturn('en');

        $this->getLocale()->shouldReturn('en');
    }

    function it_gets_deafult_locale(TranslatableListener $translatableListener)
    {
        $translatableListener->getDefaultLocale()->willReturn('en');

        $this->getDefaultLocale()->shouldReturn('en');
    }
}
