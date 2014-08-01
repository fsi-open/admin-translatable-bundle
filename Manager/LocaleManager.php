<?php

namespace FSi\Bundle\AdminTranslatableBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Artur Czocher <wasper@wasper.pl>
 */
class LocaleManager
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    /**
     * @var array
     */
    private $locales;

    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $managerRegistry
     * @param \Symfony\Component\HttpFoundation\Session\Session
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Session $session,
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
        $this->session->set('admin-locale', $locale);
        $this->setTranslatableLocale($locale);
    }

    /**
     * @param string $locale
     */
    private function setTranslatableLocale($locale)
    {
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
        if ($this->hasLocale()) {
            return $this->session->get('admin-locale');
        } else {
            return $this->getDefaultLocale();
        }
    }

    /**
     * @return bool
     */
    private function hasLocale()
    {
        return $this->session->has('admin-locale');
    }

    /**
     * @return \FSi\DoctrineExtensions\Translatable\TranslatableListener|null
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
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
