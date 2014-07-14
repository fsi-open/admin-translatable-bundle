<?php

namespace FSi\Bundle\AdminTranslatableBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FSi\Bundle\AdminBundle\Controller\ResourceController as BaseController;

class TranslatableResourceController extends BaseController
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resourceAction(AbstractResource $element, Request $request)
    {
        $context= $this->contextManager->createContext('fsi_admin_translatable_resource', $element);

        if (!isset($context)) {
            throw new NotFoundHttpException(sprintf('Cant find context builder that supports %s', $element->getName()));
        }

        if (($response = $context->handleRequest($request)) !== null) {
            return $response;
        }

        return $this->templating->renderResponse(
            $context->hasTemplateName() ? $context->getTemplateName() : $this->resourceActionTemplate,
            $context->getData()
        );
    }
}
