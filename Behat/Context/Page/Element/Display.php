<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element;

use Behat\Mink\Element\NodeElement;
use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class Display extends Element
{
    protected $selector = ['css' => 'table'];

    public function getRowValue(string $name): ?NodeElement
    {
        return $this->find('css', sprintf('td:contains("%s")+td', $name));
    }
}
