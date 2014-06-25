<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Delete;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Delete\Context as BaseContext;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Symfony\Component\Form\FormFactoryInterface;

class Context extends BaseContext
{
    /**
     * @var \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager
     */
    private $localeManager;

    /**
     * @param array $requestHandlers
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     */
    public function __construct(
        $requestHandlers,
        FormFactoryInterface $factory,
        LocaleManager $localeManager)
    {
        parent::__construct($requestHandlers, $factory);
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
