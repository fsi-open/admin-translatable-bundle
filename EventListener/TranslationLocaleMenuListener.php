<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Event\MenuEvents;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Bundle\DataGridBundle\DataGrid\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use function array_key_exists;

class TranslationLocaleMenuListener implements EventSubscriberInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var RequestMatcherInterface
     */
    private $requestMatcher;

    /**
     * @var LocaleManager
     */
    private $localeManager;

    /**
     * @var Request
     */
    private $request;

    public static function getSubscribedEvents(): array
    {
        return [
            MenuEvents::TOOLS => 'createTranslationLocaleMenu',
        ];
    }

    public function __construct(
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        RequestMatcherInterface $requestMatcher,
        LocaleManager $localeManager,
        RequestStack $requestStack
    ) {
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->requestMatcher = $requestMatcher;
        $this->localeManager = $localeManager;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function createTranslationLocaleMenu(MenuEvent $event): void
    {
        $translation = $this->createRootItem();
        $event->getMenu()->addChild($translation);

        if (false === $this->isRequestTranslatable()) {
            return;
        }

        $this->populateTranslationLocaleMenu($translation);
    }

    private function isRequestTranslatable(): bool
    {
        return array_key_exists('locale', $this->getRequestParameters());
    }

    private function getRequestParameters(): array
    {
        $query = $this->request->query->all();

        return array_merge($this->request->get('_route_params'), $query);
    }

    private function createRootItem(): Item
    {
        $translation = new Item('translation-locale');
        $translation->setLabel(
            $this->translator->trans(
                'admin.locale.dropdown.title',
                ['%locale%' => $this->localeManager->getLocale()],
                'FSiAdminTranslatableBundle'
            )
        );
        $translation->setOptions(['attr' => ['id' => 'translatable-switcher']]);

        return $translation;
    }

    private function populateTranslationLocaleMenu(Item $menu): void
    {
        $requestParameters = $this->getRequestParameters();
        $route = $this->request->get('_route');

        if (true === array_key_exists('redirect_uri', $requestParameters)) {
            $redirectRequest = $this->createRedirectRequest($requestParameters['redirect_uri']);
        }

        foreach ($this->localeManager->getLocales() as $locale) {
            $requestParameters['locale'] = $locale;

            if (isset($redirectRequest)) {
                try {
                    $requestParameters['redirect_uri'] = $this->generateRequestUriForLocale(
                        $redirectRequest,
                        $locale
                    );
                } catch (ResourceNotFoundException $e) {
                }
            }

            $localeItem = new RoutableItem(
                sprintf('translation-locale.%s', $locale),
                $route,
                $requestParameters
            );
            $localeItem->setLabel(Languages::getName($locale, $this->request->getLocale()));

            $menu->addChild($localeItem);
        }
    }

    private function createRedirectRequest(string $redirectUri): ?Request
    {
        $redirectUrlParts = parse_url($redirectUri);
        if (false === $redirectUrlParts || true === array_key_exists('host', $redirectUrlParts)) {
            return null;
        }

        $redirectServer = [
            'SCRIPT_FILENAME' => $this->request->server->get('SCRIPT_FILENAME'),
            'PHP_SELF' => $this->request->server->get('PHP_SELF'),
            'REQUEST_URI' => $redirectUrlParts['path'],
        ];
        if (true === array_key_exists('query', $redirectUrlParts)) {
            $redirectServer['QUERY_STRING'] = $redirectUrlParts['query'];
        }

        return new Request([], [], [], [], [], $redirectServer);
    }

    private function generateRequestUriForLocale(Request $redirectRequest, string $locale): string
    {
        $parameters = $this->requestMatcher->matchRequest($redirectRequest);
        if (isset($parameters['locale'])) {
            $parameters['locale'] = $locale;
        }
        $route = $parameters['_route'];
        unset($parameters['_route'], $parameters['_controller']);

        $requestUri = $this->urlGenerator->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
        if (null !== $redirectRequest->getQueryString() && '' !== $redirectRequest->getQueryString()) {
            $requestUri .= '?' . $redirectRequest->getQueryString();
        }

        return $requestUri;
    }
}
