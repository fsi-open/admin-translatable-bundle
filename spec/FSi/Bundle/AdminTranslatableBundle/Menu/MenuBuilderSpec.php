<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Knp\Menu\MenuFactory;
use Knp\Menu\MenuItem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilderSpec extends ObjectBehavior
{
    function let(
        MenuFactory $menuFactory,
        Manager $manager,
        LocaleManager $localeManager,
        Request $request,
        ParameterBag $parameterBag
    ) {
        $this->beConstructedWith($menuFactory, $manager, $localeManager);
        $request->query = $parameterBag;
        $this->setRequest($request);
    }

    function it_extends_admin_bundle_menu_builder()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Menu\MenuBuilder');
    }

    function it_creates_menu_with_translatable_elements(
        MenuFactory $menuFactory,
        Manager $manager,
        MenuItem $root,
        MenuItem $menuItem,
        TranslatableCRUDElement $element,
        LocaleManager $localeManager
    ) {
        $menuFactory->createItem('root')->willReturn($root);
        $root->setChildrenAttribute('class', 'nav navbar-nav')->shouldBeCalled();
        $root->setChildrenAttribute('id', 'top-menu')->shouldBeCalled();
        $root->setCurrentUri(Argument::any())->willReturn();

        $manager->getElementsWithoutGroup()->willReturn(array($element));
        $manager->getGroups()->willReturn(array());

        $element->getId()->willReturn('translatable_element_id');
        $element->getName()->willReturn('translatable_element_name');
        $element->getRoute()->willReturn('fsi_translatable_admin_route');
        $element->getRouteParameters()->willReturn(array('element' => 'translatable_element_id', 'locale' => 'en'));
        $element->hasOption('menu')->willReturn(true);
        $element->getOption('menu')->willReturn(true);

        $localeManager->getLocale()->willReturn('en');

        $root->addChild('translatable_element_name', array(
            'route' => 'fsi_translatable_admin_route',
            'routeParameters' => array('element' => 'translatable_element_id', 'locale' => 'en'),
        ))->shouldBeCalled();
        $root->offsetGet('translatable_element_name')->willReturn($menuItem);
        $menuItem->setAttribute('class', 'admin-element')->shouldBeCalled();

        $this->createMenu()->shouldReturn($root);
    }

    function it_creates_locales_menu(
        Request $request,
        LocaleManager $localeManager,
        ParameterBag $parameterBag,
        MenuFactory $menuFactory,
        MenuItem $rootItem,
        MenuItem $localesMenu,
        MenuItem $localeEnItem,
        MenuItem $localePlItem
    ) {
        $request->get('_route')->willReturn('fsi_admin_translatable_crud_list');
        $request->get('_route_params')->willReturn(array('element' => 'admin_news', 'locale' => 'en'));
        $parameterBag->all()->willReturn(array('additional_param' => 'some_value'));
        $localeManager->getLocales()->willReturn(array('en', 'pl'));
        $localeManager->getLocale()->willReturn('en');

        $menuFactory->createItem('locales')->willReturn($rootItem);
        $rootItem->setChildrenAttribute('class', 'nav navbar-nav navbar-right')->shouldBeCalled();
        $rootItem->setChildrenAttribute('id', 'translatable-switcher')->shouldBeCalled();

        $rootItem->addChild('admin.locale.dropdown.title', array('uri' => '#'))->willReturn($localesMenu);
        $localesMenu->setExtra('translation_params', array('%locale%' => 'en'))->shouldBeCalled();
        $localesMenu->setAttributes(array(
            'id' => 'translatable-language',
            'dropdown' => true
        ))->shouldBeCalled();

        $localesMenu->addChild('en', array(
            'route' => 'fsi_admin_translatable_crud_list',
            'routeParameters' => array(
                'element' => 'admin_news',
                'locale' => 'en',
                'additional_param' => 'some_value'
            )
        ))->willReturn($localeEnItem);
        $localeEnItem->setAttribute('class', 'active')->shouldBeCalled();

        $localesMenu->addChild('pl', array(
            'route' => 'fsi_admin_translatable_crud_list',
            'routeParameters' => array(
                'element' => 'admin_news',
                'locale' => 'pl',
                'additional_param' => 'some_value'
            )
        ))->willReturn($localePlItem);
        $localePlItem->setAttribute('class', 'active')->shouldNotBeCalled();

        $this->createLocaleMenu()->shouldReturn($rootItem);
    }

    function it_creates_empty_locales_menu_for_non_translatable_elements(
        Request $request,
        LocaleManager $localeManager,
        ParameterBag $parameterBag,
        MenuFactory $menuFactory,
        MenuItem $rootItem,
        MenuItem $localesMenu
    ) {
        $request->get('_route')->willReturn('fsi_admin_crud_list');
        $request->get('_route_params')->willReturn(array('element' => 'admin_news'));
        $parameterBag->all()->willReturn(array('additional_param' => 'some_value'));
        $localeManager->getLocales()->willReturn(array('en', 'pl'));
        $localeManager->getLocale()->willReturn('en');

        $menuFactory->createItem('locales')->willReturn($rootItem);
        $rootItem->setChildrenAttribute('class', 'nav navbar-nav navbar-right')->shouldBeCalled();
        $rootItem->setChildrenAttribute('id', 'translatable-switcher')->shouldBeCalled();

        $rootItem->addChild('admin.locale.dropdown.title', array('uri' => '#'))->willReturn($localesMenu);
        $localesMenu->setExtra('translation_params', array('%locale%' => 'en'))->shouldBeCalled();
        $localesMenu->setAttributes(array(
            'id' => 'translatable-language',
            'dropdown' => true
        ))->shouldBeCalled();

        $localesMenu->addChild(Argument::cetera())->shouldNotBeCalled();

        $this->createLocaleMenu()->shouldReturn($rootItem);
    }
}
