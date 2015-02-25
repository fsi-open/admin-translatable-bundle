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
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

class TranslationLocaleMenuListener
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Router
     */
    private $router;

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
        Router $router,
        LocaleManager $localeManager,
        RequestStack $requestStack
    ) {
        $this->translator = $translator;
        $this->router = $router;
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
                array('%locale%' => $this->localeManager->getLocale()),
                'FSiAdminTranslatableBundle'
            )
        );

        $translation->setOptions(array(
            'attr' => array(
                'id' => 'translatable-switcher',
            )
        ));

        return $translation;
    }

    private function populateTranslationLocaleMenu(Item $menu)
    {
        $requestParameters = $this->getRequestParameters();
        $route = $this->request->get('_route');

        $languageBundle = Intl::getLanguageBundle();

        if (isset($requestParameters['redirect_uri'])) {
            try {
                $redirectRequest = $this->createRedirectRequest($requestParameters['redirect_uri']);
            } catch (ResourceNotFoundException $e) { }
        }

        foreach ($this->localeManager->getLocales() as $locale) {
            $requestParameters['locale'] = $locale;

            if (isset($redirectRequest)) {
                $newRedirectPath = $this->generatePathForLocale($redirectRequest, $locale);
                $requestParameters['redirect_uri'] = $this->replaceUriPath($requestParameters['redirect_uri'], $newRedirectPath);
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
     * @param $locale
     * @return array
     */
    private function rebuildRedirectUri($redirectUri, $locale)
    {
        $redirectRequest = $this->createRedirectRequest($redirectUri);

        $newRedirectPath = $this->generatePathForLocale($redirectRequest, $locale);

        return $this->replaceUriPath($redirectUri, $newRedirectPath);
    }

    /**
     * @param string $redirectUri
     * @return Request
     */
    private function createRedirectRequest($redirectUri)
    {
        $redirectUrlParts = parse_url($redirectUri);

        $redirectServer = array(
            'SCRIPT_NAME' => $this->request->server->get('SCRIPT_NAME'),
            'SCRIPT_FILENAME' => $this->request->server->get('SCRIPT_FILENAME'),
            'HTTP_HOST' => $redirectUrlParts['host'],
            'REQUEST_URI' => $redirectUrlParts['path']
        );

        return new Request(array(), array(), array(), array(), array(), $redirectServer);
    }

    /**
     * @param Request $redirectRequest
     * @param string $locale
     * @return string
     */
    private function generatePathForLocale(Request $redirectRequest, $locale)
    {
        $parameters = $this->router->matchRequest($redirectRequest);
        if (isset($parameters['locale'])) {
            $parameters['locale'] = $locale;
        }
        $route = $parameters['_route'];
        unset($parameters['_route']);
        unset($parameters['_controller']);

        return $this->router->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /**
     * @param string $uri
     * @param string $newPath
     * @return string
     */
    private function replaceUriPath($uri, $newPath)
    {
        $uriParts = parse_url($uri);

        return sprintf('%s://%s%s%s',
            isset($uriParts['scheme']) ? $uriParts['scheme'] : 'http',
            isset($uriParts['host']) ? $uriParts['host'] : $this->request->server['HTTP_HOST'],
            $newPath,
            isset($uriParts['query']) ? ('?' . urldecode($uriParts['query'])) : ''
        );
    }
}
