<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\ListElementContext as BaseListElementContext;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

class ListElementContext extends BaseListElementContext
{
    /**
     * @var string
     */
    private $formTemplate;

    /**
     * @var LocaleManager
     */
    private $localeManager;

    public function __construct($requestHandlers, LocaleManager $localeManager, $formTemplate)
    {
        parent::__construct($requestHandlers);
        $this->formTemplate = $formTemplate;
        $this->localeManager = $localeManager;
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
            $this->element->getOption('template_form') : $this->formTemplate;
    }
}
