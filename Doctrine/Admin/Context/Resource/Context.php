<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Resource;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Resource\Context as BaseContext;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use Symfony\Component\Form\FormFactoryInterface;

class Context extends BaseContext
{
    /**
     * @var \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager
     */
    protected $localeManager;

    /**
     * @param $requestHandlers
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param \FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder $mapBuilder
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     */
    public function __construct(
        $requestHandlers,
        FormFactoryInterface $formFactory,
        MapBuilder $mapBuilder = null,
        LocaleManager $localeManager
    ) {
        parent::__construct($requestHandlers, $formFactory, $mapBuilder);
        $this->localeManager = $localeManager;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = parent::getData();
        $data['translatable_locale'] = $this->localeManager->getLocale();

        return $data;
    }
}
