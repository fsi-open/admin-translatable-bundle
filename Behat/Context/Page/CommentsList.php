<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

class CommentsList extends Page
{
    protected $path = '/admin/{locale}/list/admin_comment';
}
