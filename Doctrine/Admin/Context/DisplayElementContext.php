<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\Display\Context\DisplayContext as BaseDisplayContext;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

class DisplayElementContext extends BaseDisplayContext
{
    /**
     * @var LocaleManager
     */
    private $localeManager;

    public function __construct(
        array $requestHandlers,
        LocaleManager $localeManager,
        string $defaultTemplate
    ) {
        parent::__construct($requestHandlers, $defaultTemplate);
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
        return 'fsi_admin_translatable_display';
    }
}
