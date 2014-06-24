<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Edit;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Edit\Context as BaseContext;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

class Context extends BaseContext
{
    /**
     * @var \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager
     */
    private $localeManager;

    /**
     * @param array $requestHandlers
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     */
    public function __construct($requestHandlers, LocaleManager $localeManager)
    {
        parent::__construct($requestHandlers);
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
}
