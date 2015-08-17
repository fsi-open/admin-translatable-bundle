<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext as BaseResourceRepositoryContext;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

class ResourceRepositoryContext extends BaseResourceRepositoryContext
{
    /**
     * @var LocaleManager
     */
    private $localeManager;

    /**
     * @var string
     */
    private $defaultTemplate;

    public function __construct($requestHandlers, ResourceFormBuilder $resourceFormBuilder, LocaleManager $localeManager, $defaultTemplate)
    {
        parent::__construct($requestHandlers, $resourceFormBuilder);
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
        return 'fsi_admin_translatable_resource';
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
