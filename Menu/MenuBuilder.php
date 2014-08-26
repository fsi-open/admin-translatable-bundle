<?php

namespace FSi\Bundle\AdminTranslatableBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminBundle\Menu\MenuBuilder as BaseMenuBuilder;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableAwareInterface;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder extends BaseMenuBuilder
{
    /**
     * @var array
     */
    private $requestParameters;

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

    public function setRequest(Request $request = null)
    {
        parent::setRequest($request);

        unset($this->requestParameters);
    }

    /**
     * @param MenuItem $menu
     * @param ElementInterface $element
     */
    protected function addElementToMenu(MenuItem $menu, Elementinterface $element)
    {
        if ($element->hasOption('menu') && $element->getOption('menu') == true) {
            $menu->addChild($element->getName(), array(
                'route' => $element->getRoute(),
                'routeParameters' => $element->getRouteParameters(),
            ));
            $menu[$element->getName()]->setAttribute('class', 'admin-element');
        }
    }

    public function createLocaleMenu()
    {
        $menu = $this->createLocaleRoot();
        $localesMenu = $this->createLocaleDropdown($menu);

        if (!$this->isRequestTranslatable()) {
            return $menu;
        }

        $this->populateLocalesMenu($localesMenu);
        return $menu;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    private function createLocaleRoot()
    {
        $menu = $this->factory->createItem('locales');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu->setChildrenAttribute('id', 'translatable-switcher');

        return $menu;
    }

    /**
     * @param \Knp\Menu\MenuItem $menu
     * @return \Knp\Menu\MenuItem
     */
    private function createLocaleDropdown(MenuItem $menu)
    {
        $localesMenu = $menu->addChild('admin.locale.dropdown.title', array('uri' => '#'));
        $localesMenu->setAttributes(
            array(
                'id' => 'translatable-language',
                'dropdown' => true
            )
        );
        $localesMenu->setExtra('translation_params', array('%locale%' => $this->localeManager->getLocale()));

        return $localesMenu;
    }

    private function isRequestTranslatable()
    {
        return array_key_exists('locale', $this->getRequestParameters());
    }

    /**
     * @return array
     */
    private function getRequestParameters()
    {
        if (isset($this->requestParameters)) {
            return $this->requestParameters;
        }

        if (isset($this->request)) {
            return $this->requestParameters = array_merge(
                $this->request->get('_route_params'),
                $this->request->query->all()
            );
        } else {
            return array();
        }
    }

    /**
     * @param $localesMenu
     */
    private function populateLocalesMenu($localesMenu)
    {
        $locales = $this->localeManager->getLocales();
        foreach ($locales as $locale) {
            $this->addLocaleToMenu($localesMenu, $locale);
        }
    }

    /**
     * @param \Knp\Menu\MenuItem $localesMenu
     * @param string $locale
     */
    private function addLocaleToMenu(MenuItem $localesMenu, $locale)
    {
        $requestParameters = $this->getRequestParameters();
        $requestParameters['locale'] = $locale;

        $localeItem = $localesMenu->addChild(
            $locale,
            array(
                'route' => $this->request->get('_route'),
                'routeParameters' => $requestParameters
            )
        );

        if ($locale == $this->localeManager->getLocale()) {
            $localeItem->setAttribute('class', 'active');
        }
    }
}
