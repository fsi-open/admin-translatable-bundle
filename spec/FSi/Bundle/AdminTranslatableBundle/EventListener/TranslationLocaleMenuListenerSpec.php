<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use function array_key_exists;

class TranslationLocaleMenuListenerSpec extends ObjectBehavior
{
    public function let(
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        RequestMatcherInterface $requestMatcher,
        LocaleManager $localeManager,
        RequestStack $requestStack,
        Request $request,
        ParameterBag $query,
        ParameterBag $server
    ): void {
        $localeManager->getLocale()->willReturn('en');
        $request->getLocale()->willReturn('en');
        $request->get('_route_params')->willReturn(['element' => 'event', 'locale' => 'en']);
        $request->get('_route')->willReturn('admin_translatable_list');
        $requestStack->getCurrentRequest()->willReturn($request);

        $query->all()->willReturn([
            'param1' => 'val1',
            'redirect_uri' => '/admin/en/list/element?param=value'
        ]);
        $request->query = $query;

        $requestMatcher->matchRequest(Argument::that(function ($argument) {
            return $argument->server->get('REQUEST_URI') === '/admin/en/list/element'
                && $argument->server->get('QUERY_STRING') === 'param=value'
            ;
        }))->willReturn([
            '_route' => 'some_admin_route',
            'locale' => 'en',
            'element' => 'element'
        ]);
        $request->server = $server;

        $localeManager->getLocales()->willReturn(['pl', 'en', 'de']);

        $translator->trans(
            'admin.locale.dropdown.title',
            ['%locale%' => 'en'],
            'FSiAdminTranslatableBundle'
        )->willReturn('Menu label');

        $this->beConstructedWith(
            $translator,
            $urlGenerator,
            $requestMatcher,
            $localeManager,
            $requestStack
        );
    }

    public function it_should_create_translations_tools_menu(
        MenuEvent $menuEvent,
        UrlGeneratorInterface $urlGenerator
    ): void {
        $menuItem = new Item();
        $menuEvent->getMenu()->willReturn($menuItem);

        $urlGenerator->generate(
            'some_admin_route',
            ['locale' => 'en', 'element' => 'element'],
            UrlGeneratorInterface::ABSOLUTE_PATH)->willReturn('/admin/en/list/element');

        $urlGenerator->generate(
            'some_admin_route',
            ['locale' => 'pl', 'element' => 'element'],
            UrlGeneratorInterface::ABSOLUTE_PATH)->willReturn('/admin/pl/list/element');

        $urlGenerator->generate(
            'some_admin_route',
            ['locale' => 'de', 'element' => 'element'],
            UrlGeneratorInterface::ABSOLUTE_PATH)->willReturn('/admin/de/list/element');


        $this->createTranslationLocaleMenu($menuEvent);

        $rootItem = $menuItem->getChildren();
        $translationLocale = $rootItem['translation-locale'];

        expect($translationLocale->getLabel())->toBe('Menu label');
        expect($translationLocale->getOption('attr'))->toHaveOption('id', 'translatable-switcher');

        /** @var array<RoutableItem> $subItems */
        $subItems = $translationLocale->getChildren();

        $localePl = $subItems['translation-locale.pl'];
        $localeEn = $subItems['translation-locale.en'];
        $localeDe = $subItems['translation-locale.de'];

        expect($localePl->getLabel())->toBe('Polish');
        expect($localePl->getRoute())->toBe('admin_translatable_list');
        expect($localePl->getRouteParameters())->toBe([
            'element' => 'event',
            'locale' => 'pl',
            'param1' => 'val1',
            'redirect_uri' => '/admin/pl/list/element?param=value'
        ]);
        expect($translationLocale->getOption('attr'))->toNotHaveOption('class', 'active');

        expect($localeEn->getLabel())->toBe('English');
        expect($localeEn->getRoute())->toBe('admin_translatable_list');
        expect($localeEn->getRouteParameters())->toBe([
            'element' => 'event',
            'locale' => 'en',
            'param1' => 'val1',
            'redirect_uri' => '/admin/en/list/element?param=value'
        ]);
        expect($translationLocale->getOption('attr'))->toNotHaveOption('class', 'active');

        expect($localeDe->getLabel())->toBe('German');
        expect($localeDe->getRoute())->toBe('admin_translatable_list');
        expect($localeDe->getRouteParameters())->toBe([
            'element' => 'event',
            'locale' => 'de',
            'param1' => 'val1',
            'redirect_uri' => '/admin/de/list/element?param=value'
        ]);
        expect($translationLocale->getOption('attr'))->toNotHaveOption('class', 'active');
    }

    public function it_creates_empty_locales_menu_for_non_translatable_elements(
        MenuEvent $menuEvent,
        Request $request
    ): void {
        $menuItem = new Item();
        $menuEvent->getMenu()->willReturn($menuItem);

        $request->get('_route_params')->willReturn(['element' => 'news']);

        $this->createTranslationLocaleMenu($menuEvent);

        $rootItem = $menuItem->getChildren();
        $translationLocale = $rootItem['translation-locale'];

        expect($translationLocale->getLabel())->toBe('Menu label');
        expect($translationLocale->getOption('attr'))
            ->toHaveOption('id', 'translatable-switcher');

        expect($translationLocale->getChildren())->toBe([]);
    }

    public function getMatchers(): array
    {
        return [
            'haveOption' => function($subject, $key, $value) {
                if (false === array_key_exists($key, $subject)) {
                    return false;
                }

                return $subject[$key] === $value;
            },
        ];
    }
}
