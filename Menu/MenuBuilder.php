<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $requestParameters;

    /**
     * @var LocaleManager
     */
    private $localeManager;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var Manager
     */
    private $manager;

    /**
     * @param FactoryInterface $factory
     * @param Manager $manager
     * @param LocaleManager $localeManager
     */
    public function __construct(FactoryInterface $factory, Manager $manager, LocaleManager $localeManager)
    {
        $this->factory = $factory;
        $this->manager = $manager;
        $this->localeManager = $localeManager;
    }

    public function setRequest(Request $request = null)
    {
        $this->request = $request;

        unset($this->requestParameters);
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
     * @return ItemInterface
     */
    private function createLocaleRoot()
    {
        $menu = $this->factory->createItem('locales');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu->setChildrenAttribute('id', 'translatable-switcher');

        return $menu;
    }

    /**
     * @param MenuItem $menu
     * @return MenuItem
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
            $query = $this->request->query->all();

            if (isset($query['redirect_uri'])) {
                unset($query['redirect_uri']);
            }

            return $this->requestParameters = array_merge(
                $this->request->get('_route_params'),
                $query
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
     * @param MenuItem $localesMenu
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
