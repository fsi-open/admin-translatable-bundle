<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository;
use FSi\Bundle\AdminBundle\Controller\ResourceController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslatableResourceController extends BaseController
{
    /**
     * @param ResourceRepository\Element $element
     * @param Request $request
     * @return Response
     */
    public function resourceAction(ResourceRepository\Element $element, Request $request): Response
    {
        return $this->handleRequest($element, $request, 'fsi_admin_translatable_resource');
    }
}
