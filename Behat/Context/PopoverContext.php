<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element\Popover;

class PopoverContext extends DefaultContext
{
    /**
     * @Given /^I should see popover with value "([^"]*)" in field "([^"]*)"$/
     */
    public function iShouldSeePopoverWithField($fieldValue, string $fieldName): void
    {
        /** @var Popover $popover */
        $popover = $this->getElement('Popover');

        expect($popover->findField($fieldName)->getValue())->toBe($fieldValue);
    }

    /**
     * @Given /^I fill in field "([^"]*)" with value "([^"]*)" at popover$/
     */
    public function iFillInFieldWithValueAtPopover(string $fieldName, $fieldValue): void
    {
        /** @var Popover $popover */
        $popover = $this->getElement('Popover');

        $popover->fillField($fieldName, $fieldValue);
    }

    /**
     * @Given /^I submit popover form$/
     */
    public function iSubmitPopoverForm(): void
    {
        /** @var Popover $popover */
        $popover = $this->getElement('Popover');

        $popover->getForm()->submit();
    }

    /**
     * @Then /^I should see popover with content "([^"]*)"$/
     */
    public function iShouldSeePopoverWithContent(string $content): void
    {
        expect($this->getElement('Popover')->getContents()->getText())->toBe($content);
    }

    /**
     * @Then /^I should see popover with anchor to file$/
     */
    public function iShouldSeePopoverWithAnchorToFile(): void
    {
        expect($this->getElement('Popover')->getContents()->has('css', 'a'))->toBe(true);
    }
}
