<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\DataGrid\Extension\ColumnType;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class Action extends ColumnAbstractTypeExtension
{
    /**
     * @var LocaleManager
     */
    protected $localeManager;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(LocaleManager $localeManager, RouterInterface $router)
    {
        $this->localeManager = $localeManager;
        $this->router = $router;
    }

    public function getExtendedColumnTypes(): array
    {
        return ['action'];
    }

    public function initOptions(ColumnTypeInterface $column): void
    {
        $column->getOptionsResolver()->setNormalizer(
            'actions',
            function (Options $options, $values): array {
                foreach ($values as $action => $actionValues) {
                    $values[$action] = $this->setRouteLocale(
                        $actionValues,
                        $this->localeManager
                    );
                }

                return $values;
            }
        );
    }

    private function setRouteLocale(array $actionValues, LocaleManager $localeManager): array
    {
        if (true === in_array('locale', $this->getRouteVariables($actionValues['route_name']), true)) {
            $actionValues['additional_parameters']['locale'] = $localeManager->getLocale();
        }

        return $actionValues;
    }

    private function getRouteVariables(string $routeName): array
    {
        $route = $this->getRouteCollection()->get($routeName);
        if (null === $route) {
            return [];
        }

        return $route->compile()->getVariables();
    }

    private function getRouteCollection(): RouteCollection
    {
        return $this->router->getRouteCollection();
    }
}
