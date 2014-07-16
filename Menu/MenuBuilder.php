<?php

namespace FSi\Bundle\AdminTranslatableBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminBundle\Menu\MenuBuilder as BaseMenuBuilder;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableAwareInterface;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;

class MenuBuilder extends BaseMenuBuilder
{
    /**
     * @var \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager
     */
    private $localeManager;

    /**
     * @param \Knp\Menu\FactoryInterface $factory
     * @param \FSi\Bundle\AdminBundle\Admin\Manager $manager
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     */
    public function __construct(
        FactoryInterface $factory,
        Manager $manager,
        LocaleManager $localeManager
    ) {
        parent::__construct($factory, $manager);

        $this->localeManager = $localeManager;
    }

    /**
     * @param MenuItem $menu
     * @param ElementInterface $element
     */
    protected function addElementToMenu(MenuItem $menu, Elementinterface $element)
    {
        if ($element->hasOption('menu') && $element->getOption('menu') == true) {
            $parameters = $element->getRouteParameters();

            if ($element instanceof TranslatableAwareInterface) {
                $parameters['locale'] = $this->localeManager->getLocale();
            }

            $menu->addChild($element->getName(), array(
                'route' => $element->getRoute(),
                'routeParameters' => $parameters,
            ));
            $menu[$element->getName()]->setAttribute('class', 'admin-element');
        }
    }
}
