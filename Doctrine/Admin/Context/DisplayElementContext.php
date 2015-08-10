<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\Display\Context\DisplayContext as BaseDisplayContext;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

class DisplayElementContext extends BaseDisplayContext
{
    /**
     * @var LocaleManager
     */
    private $localeManager;

    /**
     * @var
     */
    private $defaultTemplate;

    public function __construct($requestHandlers, LocaleManager $localeManager, $defaultTemplate)
    {
        parent::__construct($requestHandlers);
        $this->localeManager = $localeManager;
        $this->defaultTemplate = $defaultTemplate;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = parent::getData();
        $data['translatable_locale'] = $this->localeManager->getLocale();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_translatable_display';
    }

    /**
     * {@inheritdoc}
     */
    public function hasTemplateName()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->hasOption('template_form') ?
            $this->element->getOption('template_form') : $this->defaultTemplate;
    }
}
