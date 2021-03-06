<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\BatchElementContext as BaseBatchElementContext;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Symfony\Component\Form\FormBuilderInterface;

class BatchElementContext extends BaseBatchElementContext
{
    /**
     * @var LocaleManager
     */
    private $localeManager;

    /**
     * @param array<HandlerInterface> $requestHandlers
     * @param FormBuilderInterface $formBuilder
     * @param LocaleManager $localeManager
     */
    public function __construct(
        array $requestHandlers,
        FormBuilderInterface $formBuilder,
        LocaleManager $localeManager
    ) {
        parent::__construct($requestHandlers, $formBuilder);
        $this->localeManager = $localeManager;
    }

    public function getData(): array
    {
        $data = parent::getData();
        $data['translatable_locale'] = $this->localeManager->getLocale();

        return $data;
    }

    protected function getSupportedRoute(): string
    {
        return 'fsi_admin_translatable_batch';
    }
}
