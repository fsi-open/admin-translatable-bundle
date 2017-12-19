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

class Form extends Element
{
    protected $selector = ['css' => 'form'];

    public function findLabel(string $label): ?NodeElement
    {
        return $this->find('css', sprintf('label:contains("%s")', $label));
    }
}
