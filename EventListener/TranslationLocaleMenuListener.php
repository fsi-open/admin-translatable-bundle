<?php

namespace FSi\Bundle\AdminTranslatableBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;

class TranslationLocaleMenuListener
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

    public function createTranslationLocaleMenu(MenuEvent $event)
    {
        $translation = $this->createRootItem();
        $event->getMenu()->addChild($translation);

        if (!$this->isRequestTranslatable()) {
            return;
        }

        $this->populateTranslationLocaleMenu($translation);
    }

    private function isRequestTranslatable()
    {
        return array_key_exists('locale', $this->getRequestParameters());
    }

    private function getRequestParameters()
    {
        $query = $this->request->query->all();

        return array_merge(
            $this->request->get('_route_params'),
            $query
        );
    }

    /**
     * @return Item
     */
    private function createRootItem()
    {
        $translation = new Item('translation-locale');

        $translation->setLabel(
            $this->translator->trans(
                'admin.locale.dropdown.title',
                ['%locale%' => $this->localeManager->getLocale()],
                'FSiAdminTranslatableBundle'
            )
        );

        $translation->setOptions([
            'attr' => [
                'id' => 'translatable-switcher',
            ]
        ]);

        return $translation;
    }

    private function populateTranslationLocaleMenu(Item $menu)
    {
        $requestParameters = $this->getRequestParameters();
        $route = $this->request->get('_route');

        $languageBundle = Intl::getLanguageBundle();

        if (isset($requestParameters['redirect_uri'])) {
            $redirectRequest = $this->createRedirectRequest($requestParameters['redirect_uri']);
        }

        foreach ($this->localeManager->getLocales() as $locale) {
            $requestParameters['locale'] = $locale;

            if (isset($redirectRequest)) {
                try {
                    $requestParameters['redirect_uri'] = $this->generateRequestUriForLocale($redirectRequest, $locale);
                } catch (ResourceNotFoundException $e) { }
            }

            $localeItem = new RoutableItem(sprintf('translation-locale.%s', $locale), $route, $requestParameters);
            $localeItem->setLabel(
                $languageBundle->getLanguageName($locale, null, $this->request->getLocale())
            );

            $menu->addChild($localeItem);
        }
    }

    /**
     * @param string $redirectUri
     * @return Request
     */
    private function createRedirectRequest($redirectUri)
    {
        $redirectUrlParts = parse_url($redirectUri);
        if (($redirectUrlParts === false) || (isset($redirectUrlParts['host']))) {
            return null;
        }

        $redirectServer = [
            'SCRIPT_FILENAME' => $this->request->server->get('SCRIPT_FILENAME'),
            'PHP_SELF' => $this->request->server->get('PHP_SELF'),
            'REQUEST_URI' => $redirectUrlParts['path'],
        ];
        if (isset($redirectUrlParts['query'])) {
            $redirectServer['QUERY_STRING'] = $redirectUrlParts['query'];
        }

        return new Request([], [], [], [], [], $redirectServer);
    }

    /**
     * @param Request $redirectRequest
     * @param string $locale
     * @return string
     */
    private function generateRequestUriForLocale(Request $redirectRequest, $locale)
    {
        $parameters = $this->requestMatcher->matchRequest($redirectRequest);
        if (isset($parameters['locale'])) {
            $parameters['locale'] = $locale;
        }
        $route = $parameters['_route'];
        unset($parameters['_route']);
        unset($parameters['_controller']);

        $requestUri = $this->urlGenerator->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
        if ($redirectRequest->getQueryString()) {
            $requestUri .= '?' . $redirectRequest->getQueryString();
        }

        return $requestUri;
    }
}
