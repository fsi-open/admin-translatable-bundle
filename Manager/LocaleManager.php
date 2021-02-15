<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Manager;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use function get_class;
use function sprintf;

class LocaleManager
{
    public const SESSION_KEY = 'admin_translatable_locale';

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var array<string>
     */
    private $locales;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param SessionInterface $session
     * @param array<string> $locales
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        SessionInterface $session,
        array $locales
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->session = $session;
        $this->locales = $locales;
    }

    /**
     * @return array<string>
     */
    public function getLocales(): array
    {
        return $this->locales;
    }

    public function setLocale(string $locale): void
    {
        $this->session->set(self::SESSION_KEY, $locale);
        $this->getTranslatableListener()->setLocale($locale);
    }

    public function getDefaultLocale(): ?string
    {
        return $this->getTranslatableListener()->getDefaultLocale();
    }

    public function getLocale(): ?string
    {
        return $this->session->get(self::SESSION_KEY, $this->getDefaultLocale());
    }

    private function getTranslatableListener(): TranslatableListener
    {
        $objectManager = $this->managerRegistry->getManager();
        if (false === $objectManager instanceof EntityManagerInterface) {
            throw new RuntimeException(
                sprintf("Expected %s but got %s", EntityManagerInterface::class, get_class($objectManager))
            );
        }

        $eventManager = $objectManager->getEventManager();
        $listener = array_reduce(
            $eventManager->getListeners(),
            function (?TranslatableListener $accumulator, array $listeners): ?TranslatableListener {
                return $accumulator ?? $this->findTranslatableListener($listeners);
            }
        );

        if (false === $listener instanceof TranslatableListener) {
            throw new RuntimeException(
                'Translatable extension is not enabled in "fsi_doctrine_extensions" section of "config.yml"'
            );
        }

        return $listener;
    }

    /**
     * @param array<EventSubscriber> $listeners
     * @return TranslatableListener|null
     */
    private function findTranslatableListener(array $listeners): ?TranslatableListener
    {
        return array_reduce(
            $listeners,
            static function (?TranslatableListener $accumulator, $listener): ?TranslatableListener {
                if (null !== $accumulator) {
                    return $accumulator;
                }

                if (true === $listener instanceof TranslatableListener) {
                    $accumulator = $listener;
                }

                return $accumulator;
            }
        );
    }
}
