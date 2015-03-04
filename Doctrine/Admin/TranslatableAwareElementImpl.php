<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

trait TranslatableAwareElementImpl
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

    protected function appendLocaleParameter(array $parameters)
    {
        $parameters['locale'] = $this->localeManager->getLocale();

        return $parameters;
    }
}
