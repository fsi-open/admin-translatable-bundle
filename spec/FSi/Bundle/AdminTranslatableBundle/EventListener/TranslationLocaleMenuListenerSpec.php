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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

class TranslationLocaleMenuListenerSpec extends ObjectBehavior
{
    function let(
        TranslatorInterface $translator,
        Router $router,
        LocaleManager $localeManager,
        RequestStack $requestStack,
        Request $request,
        ParameterBag $query,
        ParameterBag $server
    ) {
        $localeManager->getLocale()->willReturn('en');
        $request->getLocale()->willReturn('en');
        $request->get('_route_params')->willReturn(array('element' => 'event', 'locale' => 'en'));
        $request->get('_route')->willReturn('admin_translatable_list');
        $requestStack->getCurrentRequest()->willReturn($request);

        $query->all()->willReturn(array('param1' => 'val1', 'redirect_uri' => 'http://domain.local/admin/en/list/element?param=value'));
        $request->query = $query;

        $router->matchRequest(Argument::that(function ($argument) {
            return $argument->server->get('REQUEST_URI') === '/admin/en/list/element';
        }))->willReturn(array(
            '_route' => 'some_admin_route',
            'locale' => 'en',
            'element' => 'element'
        ));
        $request->server = $server;

        $localeManager->getLocales()->willReturn(array('pl', 'en', 'de'));

        $translator->trans(
            'admin.locale.dropdown.title',
            array('%locale%' => 'en'),
            'FSiAdminTranslatableBundle'
        )->willReturn('Menu label');

        $this->beConstructedWith($translator, $router, $localeManager, $requestStack);
    }

    function it_should_create_translations_tools_menu(MenuEvent $menuEvent, Router $router)
    {
        $menuItem = new Item();
        $menuEvent->getMenu()->willReturn($menuItem);

        $router->generate(
            'some_admin_route',
            array('locale' => 'en', 'element' => 'element'),
            UrlGeneratorInterface::ABSOLUTE_PATH)->willReturn('/admin/en/list/element');

        $router->generate(
            'some_admin_route',
            array('locale' => 'pl', 'element' => 'element'),
            UrlGeneratorInterface::ABSOLUTE_PATH)->willReturn('/admin/pl/list/element');

        $router->generate(
            'some_admin_route',
            array('locale' => 'de', 'element' => 'element'),
            UrlGeneratorInterface::ABSOLUTE_PATH)->willReturn('/admin/de/list/element');


        $this->createTranslationLocaleMenu($menuEvent);

        $rootItem = $menuItem->getChildren();
        $translationLocale = $rootItem['translation-locale'];

        expect($translationLocale->getLabel())->toBe('Menu label');
        expect($translationLocale->getOption('attr'))->toHaveOption('id', 'translatable-switcher');

        /** @var \FSi\Bundle\AdminBundle\Menu\Item\RoutableItem[] $subItems */
        $subItems = $translationLocale->getChildren();

        $localePl = $subItems['translation-locale.pl'];
        $localeEn = $subItems['translation-locale.en'];
        $localeDe = $subItems['translation-locale.de'];

        expect($localePl->getLabel())->toBe('Polish');
        expect($localePl->getRoute())->toBe('admin_translatable_list');
        expect($localePl->getRouteParameters())->toBe(array(
            'element' => 'event',
            'locale' => 'pl',
            'param1' => 'val1',
            'redirect_uri' => 'http://domain.local/admin/pl/list/element?param=value'
        ));
        expect($translationLocale->getOption('attr'))->toNotHaveOption('class', 'active');

        expect($localeEn->getLabel())->toBe('English');
        expect($localeEn->getRoute())->toBe('admin_translatable_list');
        expect($localeEn->getRouteParameters())->toBe(array(
            'element' => 'event',
            'locale' => 'en',
            'param1' => 'val1',
            'redirect_uri' => 'http://domain.local/admin/en/list/element?param=value'
        ));
        expect($translationLocale->getOption('attr'))->toNotHaveOption('class', 'active');

        expect($localeDe->getLabel())->toBe('German');
        expect($localeDe->getRoute())->toBe('admin_translatable_list');
        expect($localeDe->getRouteParameters())->toBe(array(
            'element' => 'event',
            'locale' => 'de',
            'param1' => 'val1',
            'redirect_uri' => 'http://domain.local/admin/de/list/element?param=value'
        ));
        expect($translationLocale->getOption('attr'))->toNotHaveOption('class', 'active');
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
        expect($translationLocale->getOption('attr'))
            ->toHaveOption('id', 'translatable-switcher');

        expect($translationLocale->getChildren())->toBe(array());
    }

    public function getMatchers()
    {
        return [
            'haveOption' => function($subject, $key, $value) {
                if (!isset($subject[$key])) {
                    return false;
                }

                return $subject[$key] === $value;
            },
        ];
    }
}
