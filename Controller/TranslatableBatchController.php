<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Controller\BatchController as BaseBatchController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslatableBatchController extends BaseBatchController
{
    public function batchAction(BatchElement $element, Request $request): Response
    {
        return $this->handleRequest($element, $request, 'fsi_admin_translatable_batch');
    }
}
