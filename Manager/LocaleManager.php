<?php

namespace FSi\Bundle\AdminTranslatableBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $managerRegistry
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \Symfony\Component\HttpFoundation\Session\Session
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        ContainerInterface $container,
        Session $session
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->session = $session;
        $this->container = $container;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        if (!$this->hasLocale()) {
            $this->session->set('admin-locale', $this->getLocaleParameter());
        } elseif (!empty($locale)) {
            $this->session->set('admin-locale', $locale);
        }

        $this->setTranslatableLocale($this->getLocale());
    }

    /**
     * @param string $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->getTranslatableListener()->setLocale($locale);
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->session->get('admin-locale');
    }

    /**
     * @return bool
     */
    public function hasLocale()
    {
        return $this->session->has('admin-locale');
    }

    /**
     * @return string
     */
    private function getLocaleParameter()
    {
        return $this->container->getParameter('locale');
    }


    private function getTranslatableListener()
    {
        $evm = $this->managerRegistry->getManager()->getEventManager();
        foreach ($evm->getListeners() as $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof TranslatableListener) {
                    return $listener;
                }
            }
        }
    }
}
