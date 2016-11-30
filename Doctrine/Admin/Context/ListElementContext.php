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
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableListElement;

class ListElementContext extends BaseListElementContext
{
    /**
     * @var LocaleManager
     */
    private $localeManager;

    /**
     * @var string
     */
    private $listTemplate;

    public function __construct($requestHandlers, LocaleManager $localeManager, $listTemplate)
    {
        parent::__construct($requestHandlers);
        $this->localeManager = $localeManager;
        $this->listTemplate = $listTemplate;
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
        return $this->element->hasOption('template_list') ?
            $this->element->getOption('template_list') : $this->listTemplate;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_translatable_list';
    }

    /**
     * {@inheritdoc}
     */
    public function supportsElement(Element $element)
    {
        if (!parent::supportsElement($element)) {
            return false;
        }

        return $element instanceof TranslatableListElement;
    }
}
