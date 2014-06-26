<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Symfony\Component\HttpKernel\KernelInterface;

class TranslatableCRUDContext extends PageObjectContext implements KernelAwareInterface
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Then /^I should see list with following columns$/
     */
    public function iShouldSeeListWithFollowingColumns(TableNode $columns)
    {
        foreach ($columns->getHash() as $column) {
            expect($this->getElement('Grid')->hasColumn($column['Column name']))->toBe(true);
        }
    }

    /**
     * @Given /^I see events with name values$/
     */
    public function iSeeEventsWithColumnValues(TableNode $elements)
    {
        foreach ($elements->getHash() as $element) {
            expect($this->getElement('Grid')->hasEventNameCellWithValue($element['Name']))->toBe(true);
        }
    }

    /**
     * @Then /^I should see event with default name "([^"]*)"$/
     * @And /^I should see event with name "([^"]*)"$/
     * @Then /^I should see event with name "([^"]*)"$/
     */
    public function iShouldSeeEventWithDefaultName($eventName)
    {
        expect($this->getElement('Grid')->hasEventNameCellWithValue($eventName))->toBe(true);
    }

    /**
     * @Then /^I should see event with empty name value$/
     */
    public function iShouldSeeEventWithEmptyNameValue()
    {
        expect($this->getElement('Grid')->hasEventNameCellWithValue(''))->toBe(true);
    }

    /**
     * @Given /^I edit only event element$/
     */
    public function iEditOnlyEventElement()
    {
        $this->getElement('Grid')->editOnlyEvent();
    }

    /**
     * @Given /^I change form "([^"]*)" field value to "([^"]*)"$/
     */
    public function iChangeFormFieldValueTo($field, $value)
    {
        $this->getElement('Form')->fillField($field, $value);
    }

    /**
     * @Given /^I press form "([^"]*)" button$/
     */
    public function iPressFormButton($button)
    {
        $this->getElement('Form')->pressButton($button);
    }
}
