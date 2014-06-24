<?php

namespace FSi\Bundle\AdminTranslatableBundle\Controller;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;

class LocaleController
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    private $templating;

    /**
     * @var \Symfony\Component\Routing\Router
     */
    private $router;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager
     */
    private $localeManager;

    /**
     * @var array
     */
    private $locales;

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param \Symfony\Component\Routing\Router $router
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     * @param array $locales
     */
    public function __construct(
        EngineInterface $templating,
        Router $router,
        RequestStack $requestStack,
        LocaleManager $localeManager,
        array $locales
    ) {
        $this->templating = $templating;
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->localeManager = $localeManager;
        $this->locales = $locales;
    }

    /**
     * @return \Symfony\Component\Routing\Route
     */
    public function indexAction()
    {
        return $this->templating->renderResponse(
            'FSiAdminTranslatableBundle:Locale:index.html.twig', array(
            'routes' => $this->generateRoutes(),
            'currentLocale' => $this->getCurrentLocale(),
            'isTranslatable' => $this->hasTranslatableElement(),
        ));
    }

    /**
     * @return array
     */
    private function getLocales()
    {
        return $this->locales;
    }

    /**
     * @return array
     */
    private function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getMasterRequest()
    {
        return $this->requestStack->getMasterRequest();
    }

    /**
     * @return string
     */
    private function getRoute()
    {
        return $this->getMasterRequest()->get('_route');
    }

    /**
     * @return array
     */
    private function getOriginalParameters()
    {
        return $this->parameters = array_merge(
            $this->getMasterRequest()->get('_route_params'),
            $this->getMasterRequest()->query->all()
        );
    }

    /**
     * @return array
     */
    private function generateRoutes()
    {
        $this->getOriginalParameters();
        $routes = array();

        foreach ($this->getLocales() as $locale) {
            $routes[$locale] = $this->generateRouteForLocale($locale);
        }

        return $routes;
    }

    /**
     * @param string $language
     * @return string
     */
    private function generateRouteForLocale($language)
    {
        $this->setParameter('locale', $language);

        return $this->router->generate(
            $this->getRoute(),
            $this->getParameters()
        );
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->localeManager->getLocale();
    }

    /**
     * @return bool
     */
    private function hasTranslatableElement()
    {
        return array_key_exists('locale', $this->getOriginalParameters());
    }
}
