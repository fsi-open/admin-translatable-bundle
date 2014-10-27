<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Resource;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Resource\Context as BaseContext;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface;

class Context extends BaseContext
{
    /**
     * @var \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager
     */
    protected $localeManager;

    /**
     * @param $requestHandlers
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     * @param \FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder $mapBuilder
     */
    public function __construct(
        $requestHandlers,
        FormFactoryInterface $formFactory,
        LocaleManager $localeManager,
        MapBuilder $mapBuilder = null
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

    /**
     * @param array $resources
     * @return array
     */
    protected function createFormData(array $resources)
    {
        $data = array();

        foreach ($resources as $resourceKey => $resource) {
            if ($resource instanceof ResourceInterface) {
                $resourceName = $this->element->getKey() . "." . $resourceKey;
                $data[$this->normalizeKey($resourceName)]
                    = $this->element->getRepository()->get($resource->getName());
            }
        }

        return $data;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $resources
     */
    protected function buildForm(FormBuilderInterface $builder, array $resources)
    {
        foreach ($resources as $resourceKey => $resource) {
            if ($resource instanceof ResourceInterface) {
                $resourceName = $this->element->getKey() . "." . $resourceKey;
                $builder->add(
                    $this->normalizeKey($resourceName),
                    'resource',
                    array(
                        'resource_key' => $resourceName,
                        'translatable_locale' => $this->localeManager->getLocale()
                    )
                );
            }
        }
    }

}
