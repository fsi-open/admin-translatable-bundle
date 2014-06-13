<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Manager;

use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class LocaleManagerSpec extends ObjectBehavior
{
    function let(
        ManagerRegistry $managerRegistry,
        EntityManager $entityManager,
        EventManager $eventManager,
        TranslatableListener $translatableListener,
        ContainerInterface $container,
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

        $this->beConstructedWith($managerRegistry, $container, $session);
    }

    function it_sets_default_locale(
        Session $session,
        ContainerInterface $container,
        TranslatableListener $translatableListener
    ) {
        $session->has('admin-locale')->willReturn(false);
        $container->getParameter('locale')->willReturn('pl');

        $session->set('admin-locale', 'pl')->shouldBeCalled();
        $session->get('admin-locale')->willReturn('pl');

        $translatableListener->setLocale('pl')->shouldBeCalled();

        $this->setLocale('pl');
    }

    function it_sets_current_locale(
        Session $session,
        TranslatableListener $translatableListener
    ) {
        $session->has('admin-locale')->willReturn(true);

        $session->set('admin-locale', 'pl')->shouldBeCalled();
        $session->get('admin-locale')->willReturn('pl');

        $translatableListener->setLocale('pl')->shouldBeCalled();

        $this->setLocale('pl');
    }
}