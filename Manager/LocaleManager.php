<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LocaleManager
{
    const SESSION_KEY = 'admin_translatable_locale';

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var array
     */
    private $locales;

    public function __construct(
        ManagerRegistry $managerRegistry,
        SessionInterface $session,
        array $locales
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->session = $session;
        $this->locales = $locales;
    }

    public function getLocales()
    {
        return $this->locales;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->session->set(self::SESSION_KEY, $locale);
        $this->getTranslatableListener()->setLocale($locale);
    }

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->getTranslatableListener()->getDefaultLocale();
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->session->get(self::SESSION_KEY, $this->getDefaultLocale());
    }

    /**
     * @return TranslatableListener|null
     * @throws RuntimeException
     */
    private function getTranslatableListener()
    {
        $eventManager = $this->managerRegistry->getManager()->getEventManager();
        foreach ($eventManager->getListeners() as $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof TranslatableListener) {
                    return $listener;
                }
            }
        }

        throw new RuntimeException('Translatable extension is not enabled in "fsi_doctrine_extensions" section of "config.yml"');
    }
}
