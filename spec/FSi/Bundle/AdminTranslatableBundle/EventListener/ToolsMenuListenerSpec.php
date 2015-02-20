<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

class ToolsMenuListenerSpec extends ObjectBehavior
{
    function let(
        TranslatorInterface $translator,
        LocaleManager $localeManager,
        RequestStack $requestStack,
        Request $request,
        ParameterBag $query
    ) {
        $localeManager->getLocale()->willReturn('pl');

        $request->get('_route_params')->willReturn(array('element' => 'event', 'locale' => 'pl'));
        $request->get('_route')->willReturn('admin_translatable_list');
        $requestStack->getCurrentRequest()->willReturn($request);

        $query->all()->willReturn(array('param1' => 'val1'));
        $request->query = $query;

        $localeManager->getLocales()->willReturn(array('pl', 'en', 'de'));

        $translator->trans(
            'admin.locale.dropdown.title',
            array('%locale%' => 'pl'),
            'FSiAdminTranslatableBundle'
        )->willReturn('Menu label');

        $this->beConstructedWith($translator, $localeManager, $requestStack);
    }

    function it_should_create_trainslations_tools_menu(MenuEvent $menuEvent)
    {
        $menuItem = new Item();
        $menuEvent->getMenu()->willReturn($menuItem);

        $this->createTranslationLocaleMenu($menuEvent);

        $rootItem = $menuItem->getChildren();
        $translationLocale = $rootItem['translation-locale'];

        expect($translationLocale->getLabel())->toBe('Menu label');
        expect($translationLocale->getOption('id'))->toBe('translatable-switcher');

        /** @var \FSi\Bundle\AdminBundle\Menu\Item\RoutableItem[] $subItems */
        $subItems = $translationLocale->getChildren();

        $localePl = $subItems['translation-locale.pl'];
        $localeEn = $subItems['translation-locale.en'];
        $localeDe = $subItems['translation-locale.de'];

        expect($localePl->getLabel())->toBe('pl');
        expect($localePl->getRoute())->toBe('admin_translatable_list');
        expect($localePl->getRouteParameters())->toBe(array('element' => 'event', 'locale' => 'pl', 'param1' => 'val1'));
        expect($localePl->getOptions())->toBe(array('id' => null, 'class' => 'active'));

        expect($localeEn->getLabel())->toBe('en');
        expect($localeEn->getRoute())->toBe('admin_translatable_list');
        expect($localeEn->getRouteParameters())->toBe(array('element' => 'event', 'locale' => 'en', 'param1' => 'val1'));
        expect($localeEn->getOptions())->toBe(array('id' => null, 'class' => null));

        expect($localeDe->getLabel())->toBe('de');
        expect($localeDe->getRoute())->toBe('admin_translatable_list');
        expect($localeDe->getRouteParameters())->toBe(array('element' => 'event', 'locale' => 'de', 'param1' => 'val1'));
        expect($localeDe->getOptions())->toBe(array('id' => null, 'class' => null));
    }

    function it_creates_empty_locales_menu_for_non_translatable_elements(MenuEvent $menuEvent, Request $request)
    {
        $menuItem = new Item();
        $menuEvent->getMenu()->willReturn($menuItem);

        $request->get('_route_params')->willReturn(array('element' => 'news'));

        $this->createTranslationLocaleMenu($menuEvent);

        $rootItem = $menuItem->getChildren();
        $translationLocale = $rootItem['translation-locale'];

        expect($translationLocale->getLabel())->toBe('Menu label');
        expect($translationLocale->getOption('id'))->toBe('translatable-switcher');

        expect($translationLocale->getChildren())->toBe(array());
    }
}
