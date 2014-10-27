<?php

namespace FSi\Bundle\AdminTranslatableBundle\DataGrid\Extension\ColumnType;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Routing\RouterInterface;

class Action extends ColumnAbstractTypeExtension
{
    /**
     * @var \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager
     */
    protected $localeManager;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(
        LocaleManager $localeManager,
        RouterInterface $router
    ) {
        $this->localeManager = $localeManager;
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function getExtendedColumnTypes()
    {
        return array(
            'action'
        );
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setNormalizers(array(
            'actions' => function (Options $options, $values) {
                    foreach ($values as $action => $actionValues) {
                        $values[$action] = $this->setRouteLocale($actionValues, $this->localeManager);
                    }
                    return $values;
                }
        ));
    }

    /**
     * @param array $actionValues
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     * @return mixed
     */
    private function setRouteLocale($actionValues, $localeManager)
    {
        if (in_array('locale', $this->getRouteVariables($actionValues['route_name']))) {
            $actionValues['additional_parameters']['locale'] = $localeManager->getLocale();
        }

        return $actionValues;
    }

    /**
     * @param string $route
     * @return array
     */
    private function getRouteVariables($route)
    {
        $route = $this->getRouteCollection()->get($route);

        if ($route) {
            return $route->compile()->getVariables();
        } else {
            return array();
        }
    }

    /**
     * @return null|\Symfony\Component\Routing\RouteCollection
     */
    private function getRouteCollection()
    {
        return $this->router->getRouteCollection();
    }
}
