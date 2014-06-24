<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Create\Context as CreateContext;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Delete\Context as DeleteContext;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Read\Context as ReadContext;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Edit\Context as EditContext;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslatableCRUDControllerSpec extends ObjectBehavior
{
    function let(ContextManager $manager, EngineInterface $templating)
    {
        $this->beConstructedWith(
            $templating,
            $manager,
            'translatable_crud_list',
            'translatable_crud_create',
            'translatable_crud_edit',
            'translatable_crud_delete'
        );
    }

    function it_render_template_in_list_action(
        Request $request,
        Response $response,
        AbstractCRUD $element,
        ContextManager $manager,
        ReadContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_translatable_crud_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('translatable_crud_list', array(), null)->willReturn($response);
        $this->listAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_in_create_action(
        Request $request,
        Response $response,
        AbstractCRUD $element,
        ContextManager $manager,
        CreateContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_translatable_crud_create', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('translatable_crud_create', array(), null)->willReturn($response);
        $this->createAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_in_edit_action(
        Request $request,
        Response $response,
        AbstractCRUD $element,
        ContextManager $manager,
        EditContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_translatable_crud_edit', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('translatable_crud_edit', array(), null)->willReturn($response);
        $this->editAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_in_delete_action(
        Request $request,
        Response $response,
        AbstractCRUD $element,
        ContextManager $manager,
        DeleteContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_translatable_crud_delete', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('translatable_crud_delete', array(), null)->willReturn($response);
        $this->deleteAction($element, $request)->shouldReturn($response);
    }
}
