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

    public function __construct($requestHandlers, ResourceFormBuilder $resourceFormBuilder, LocaleManager $localeManager, $defaultTemplate)
    {
        parent::__construct($requestHandlers, $defaultTemplate, $resourceFormBuilder);
        $this->localeManager = $localeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $data = parent::getData();
        $data['translatable_locale'] = $this->localeManager->getLocale();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedRoute(): string
    {
        return 'fsi_admin_translatable_resource';
    }
}
