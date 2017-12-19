<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page;

use Behat\Mink\Element\NodeElement;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\ElementNotFoundException;

class TranslatableResourceEdit extends Page
{
    protected $path = '/admin/{locale}/resource/translatable_resource';

    public function getHeader(): ?NodeElement
    {
        return $this->find('css', '#page-header')->getText();
    }

    protected function verifyPage(): void
    {
        if (!$this->has('css', '#page-header:contains("Edit resources")')) {
            throw new ElementNotFoundException(sprintf('%s page is missing "Edit resources" header', $this->path));
        }
    }
}
