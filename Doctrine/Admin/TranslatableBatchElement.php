<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\BatchElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

abstract class TranslatableBatchElement extends BatchElement implements TranslatableAwareInterface
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

    public function getRoute()
    {
        return 'getSuccessRouteParameters';
    }

    public function getRouteParameters()
    {
        $parameters = parent::getRouteParameters();
        $parameters['locale'] = $this->localeManager->getLocale();;

        return $parameters;
    }

    public function getSuccessRoute()
    {
        return parent::getSuccessRoute();
    }

    public function getSuccessRouteParameters()
    {
        $parameters = parent::getSuccessRouteParameters();
        $parameters['locale'] = $this->localeManager->getLocale();;

        return $parameters;
    }

}
