<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\Manager;

use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LocaleManagerSpec extends ObjectBehavior
{
    const DE = 'de';
    const EN = 'en';
    const PL = 'pl';

    function let(
        ManagerRegistry $managerRegistry,
        EntityManager $entityManager,
        EventManager $eventManager,
        TranslatableListener $translatableListener,
        SessionInterface $session
    ) {
        $managerRegistry->getManager()->willReturn($entityManager);
        $entityManager->getEventManager()->willReturn($eventManager);
        $eventManager->getListeners()->willReturn(['preFlush' => [$translatableListener]]);

        $this->beConstructedWith($managerRegistry, $session, [self::EN, self::DE]);
    }

    function it_returns_configured_locales()
    {
        $this->getLocales()->shouldReturn([self::EN, self::DE]);
    }

    function it_sets_locale(
        SessionInterface $session,
        TranslatableListener $translatableListener
    ) {

        $session->set(LocaleManager::SESSION_KEY, self::PL)->shouldBeCalled();
        $translatableListener->setLocale(self::PL)->shouldBeCalled();

        $this->setLocale(self::PL);
    }

    function it_gets_default_locale_when_session_is_empty(
        SessionInterface $session,
        TranslatableListener $translatableListener
    ) {
        $translatableListener->getDefaultLocale()->willReturn(self::EN);
        $session->get(LocaleManager::SESSION_KEY, self::EN)->willReturn(self::EN);

        $this->getLocale()->shouldReturn(self::EN);
    }

    function it_gets_locale_when_session_is_not_empty(
        SessionInterface $session,
        TranslatableListener $translatableListener
    ) {
        $translatableListener->getDefaultLocale()->willReturn(self::PL);
        $session->get(LocaleManager::SESSION_KEY, self::PL)->willReturn(self::EN);

        $this->getLocale()->shouldReturn(self::EN);
    }

    function it_gets_deafult_locale(TranslatableListener $translatableListener)
    {
        $translatableListener->getDefaultLocale()->willReturn(self::EN);

        $this->getDefaultLocale()->shouldReturn(self::EN);
    }
}
