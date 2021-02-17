<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\ListElementContext as BaseListElementContext;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableListElement;

class ListElementContext extends BaseListElementContext
{
    /**
     * @var LocaleManager
     */
    private $localeManager;

    public function __construct(
        array $requestHandlers,
        LocaleManager $localeManager,
        string $template
    ) {
        parent::__construct($requestHandlers, $template);
        $this->localeManager = $localeManager;
    }

    public function getData(): array
    {
        $data = parent::getData();
        $data['translatable_locale'] = $this->localeManager->getLocale();

        return $data;
    }

    public function supportsElement(Element $element): bool
    {
        if (false === parent::supportsElement($element)) {
            return false;
        }

        return $element instanceof TranslatableListElement;
    }

    protected function getSupportedRoute(): string
    {
        return 'fsi_admin_translatable_list';
    }
}
