<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

abstract class TranslatableCRUDElement extends CRUDElement implements TranslatableAwareInterface
{
    /**
     * @var LocaleManager
     */
    protected $localeManager;

    /**
     * @param LocaleManager $localeManager
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
        return 'fsi_admin_translatable_list';
    }

    public function getRouteParameters()
    {
        $parameters = parent::getRouteParameters();
        $parameters['locale'] = $this->localeManager->getLocale();

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRoute()
    {
        return $this->getRoute();
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRouteParameters()
    {
        $parameters = parent::getSuccessRouteParameters();
        $parameters['locale'] = $this->localeManager->getLocale();;

        return $parameters;
    }
}
