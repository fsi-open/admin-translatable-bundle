<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;

class PopoverContext extends PageObjectContext
{
    /**
     * @Given /^I should see popover with value "([^"]*)" in field "([^"]*)"$/
     */
    public function iShouldSeePopoverWithField($fieldValue, $fieldName)
    {
        /** @var \FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element\Popover $popover */
        $popover = $this->getElement('Popover');

        expect($popover->findField($fieldName)->getValue())->toBe($fieldValue);
    }

    /**
     * @Given /^I fill in field "([^"]*)" with value "([^"]*)" at popover$/
     */
    public function iFillInFieldWithValueAtPopover($fieldName, $fieldValue)
    {
        /** @var \FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element\Popover $popover */
        $popover = $this->getElement('Popover');

        $popover->fillField($fieldName, $fieldValue);
    }

    /**
     * @Given /^I submit popover form$/
     */
    public function iSubmitPopoverForm()
    {
        /** @var \FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element\Popover $popover */
        $popover = $this->getElement('Popover');

        $popover->getForm()->submit();
    }
}
