<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\FormElementContext as BaseFormElementContext;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableFormElement;

class FormElementContext extends BaseFormElementContext
{
    /**
     * @var LocaleManager
     */
    private $localeManager;

    public function __construct(
        array $requestHandlers,
        LocaleManager $localeManager,
        string $formTemplate
    ) {
        parent::__construct($requestHandlers, $formTemplate);
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
        return 'fsi_admin_translatable_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsElement(Element $element): bool
    {
        return $element instanceof TranslatableFormElement;
    }
}
