<?php

namespace FSi\Bundle\AdminTranslatableBundle\DataGrid\Extension\ColumnType;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Routing\Router;

class Action extends ColumnAbstractTypeExtension
{
    protected $localeManager;

    protected $container;

    protected $router;

    public function __construct(
        LocaleManager $localeManager,
        Router $router
    ) {
        $this->localeManager = $localeManager;
        $this->router = $router;
    }

    public function getExtendedColumnTypes()
    {
        return array(
            'action'
        );
    }

    public function initOptions(ColumnTypeInterface $column)
    {
        $localeManager = $this->localeManager;

        $column->getOptionsResolver()->setNormalizers(array(
            'actions' => function (Options $options, $values) use ($localeManager) {
                    foreach ($values as $action => $actionValues) {
                        $values[$action] = $this->setRouteLocale($actionValues, $localeManager);
                    }
                    return $values;
                }
        ));
    }

    private function setRouteLocale($actionValues, $localeManager)
    {
        if (in_array('locale', $this->getRouteVariables($actionValues['route_name']))) {
            $actionValues['additional_parameters']['locale'] = $localeManager->getLocale();
        }

        return $actionValues;
    }

    private function getRouteVariables($route)
    {
        return $this->getRouteCollection()
            ->get($route)
            ->compile()
            ->getVariables();
    }

    private function getRouteCollection()
    {
        return $this->router->getRouteCollection();
    }
}
