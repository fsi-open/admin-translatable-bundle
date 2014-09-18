<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement as BaseCRUD;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

abstract class TranslatableCRUDElement extends BaseCRUD implements TranslatableAwareInterface
{
    /**
     * @var \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager
     */
    protected $localeManager;

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_translatable_crud_list';
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

    /**
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     */
    public function setLocaleManager(LocaleManager $localeManager)
    {
        $this->localeManager = $localeManager;
    }
}
