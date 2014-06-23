<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Controller;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

class LocaleControllerSpec extends ObjectBehavior
{
    function let(
        EngineInterface $templating,
        Router $router,
        RequestStack $requestStack,
        LocaleManager $localeManager
    ) {
        $this->beConstructedWith($templating, $router, $requestStack, $localeManager, array('pl', 'en'));
    }

    function it_render_template_with_locale_dropdown(
        EngineInterface $templating,
        RequestStack $requestStack,
        Request $request,
        Router $router,
        LocaleManager $localeManager,
        ParameterBag $parameterBag,
        Response $response
    ) {
        $requestStack->getMasterRequest()->willReturn($request);
        $request->get('_route_params')->willReturn(array('element' => 'admin_news', 'locale' => 'en'));
        $request->query = $parameterBag;
        $parameterBag->all()->willReturn(array());
        $request->get('_route')->willReturn('fsi_admin_translatable_crud_list');
        $localeManager->getLocale()->willReturn('en');

        $router->generate(
            'fsi_admin_translatable_crud_list',
            array('element' => 'admin_news', 'locale' => 'en')
        )->willReturn('admin/en/admin_news/list');

        $router->generate(
            'fsi_admin_translatable_crud_list',
            array('element' => 'admin_news', 'locale' => 'pl')
        )->willReturn('admin/pl/admin_news/list');

        $templating->renderResponse(
            'FSiAdminTranslatableBundle:Locale:index.html.twig', array(
                'routes' => array(
                    'en' => 'admin/en/admin_news/list',
                    'pl' => 'admin/pl/admin_news/list'
                ),
                'currentLocale' => 'en',
                'isTranslatable' => 'true',
            )
        )->willReturn($response);

        $this->indexAction()->shouldReturn($response);
    }
}
