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
            ->willReturn(array(
                'preFlush' => array(
                    $translatableListener
                )
            ));

        $this->beConstructedWith($managerRegistry, $session);
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
        $translatableListener->getDefaultLocale()->shouldBeCalled();

        $this->getLocale();
    }

    function it_gets_locale_when_session_is_not_empty(
        Session $session
    ) {
        $session->has('admin-locale')->willReturn(true);
        $session->get('admin-locale')->shouldBeCalled();

        $this->getLocale();
    }
}
