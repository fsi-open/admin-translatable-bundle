<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement as BaseElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

abstract class TranslatableResourceElement extends BaseElement implements TranslatableAwareInterface
{
    /**
     * @var \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager
     */
    protected $localeManager;

    /**
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     */
    public function setLocaleManager(LocaleManager $localeManager)
    {
        $this->localeManager = $localeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_translatable_resource';
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteParameters()
    {
        $routeParameters = parent::getRouteParameters();
        $routeParameters['locale'] = $this->localeManager->getLocale();

        return $routeParameters;
    }
}
