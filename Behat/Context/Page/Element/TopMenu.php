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
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\ElementNotFoundException;

class TopMenu extends Element
{
    protected $selector = ['css' => 'div.navbar.navbar-inverse.navbar-fixed-top'];

    public function getTranslatableSwitcher(): ?NodeElement
    {
        return $this->find('css', '#translatable-switcher');
    }

    public function hasTranslatableSwitcher(): bool
    {
        return $this->has('css', '#translatable-switcher');
    }

    public function hasFollowingLocales($locale): bool
    {
        return $this->getTranslatableSwitcher()->has('css', sprintf('li > ul li a:contains(%s)', $locale));
    }

    public function isTranslatableSwitcherActive(): bool
    {
        return $this->getTranslatableSwitcher()->has('css', 'li > ul.dropdown-menu');
    }

    public function hasActiveTranslatableLanguage($locale): bool
    {
        return $this->has('css', sprintf('#translatable-switcher ul li.active:contains(%s)', $locale));
    }

    public function findTranslatableLanguageElement($translatableLocale): ?NodeElement
    {
        $selector = sprintf('#translatable-switcher ul li a:contains("%s")', $translatableLocale);
        $element = $this->find('css', $selector);

        if (null === $element) {
            throw new ElementNotFoundException(sprintf('Unable to find %s', $selector));
        }

        return $element;
    }

    public function clickTranslatableDropdown(): void
    {
        $this->find('css', '#translatable-switcher a.dropdown-toggle')->click();
    }
}
