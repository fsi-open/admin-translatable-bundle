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
     * @Then /^I should see simple text filter "([^"]*)"$/
     */
    public function iShouldSeeSimpleTextFilter($filterName)
    {
        expect($this->getElement('Filters')->hasField($filterName))->toBe(true);
    }

    /**
     * @Given /^I fill simple text filter "([^"]*)" with value "([^"]*)"$/
     */
    public function iFillSimpleTextFilterWithValue($filterName, $filterValue)
    {
        $this->getElement('Filters')->fillField($filterName, $filterValue);
    }

    /**
     * @Given /^simple text filter "([^"]*)" should be filled with value "([^"]*)"$/
     */
    public function simpleTextFilterShouldBeFilledWithValue($filterName, $filterValue)
    {
        expect($this->getElement('Filters')->findField($filterName)->getValue())->toBe($filterValue);
    }

    /**
     * @Given /^I should see events with following names$/
     */
    public function iShouldSeeEventsWithFollowingNames(TableNode $elements)
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
     * @Then /^I should see event with empty name$/
     */
    public function iShouldSeeEventWithEmptyName()
    {
        expect($this->getElement('Grid')->hasEventNameCellWithValue(''))->toBe(true);
    }

    /**
     * @Given /^I edit first event on the list$/
     */
    public function iEditFirstEventOnTheList()
    {
        $this->getElement('Grid')->clickEdit();
    }

    /**
     * @Given /^I display first event on the list$/
     */
    public function iDisplayFirstEventOnTheList()
    {
        $this->getElement('Grid')->clickDisplay();
    }

    /**
     * @Given /^I change "([^"]*)" field value to "([^"]*)"$/
     */
    public function iChangeFieldValueTo($field, $value)
    {
        $this->getElement('Form')->fillField($field, $value);
    }

    /**
     * @Given /^I press "([^"]*)" button$/
     */
    public function iPressButton($button)
    {
        $this->getElement('Form')->pressButton($button);
    }

    /**
     * @When /^I check first item on the list$/
     */
    public function iCheckFirstItemOnTheList()
    {
        $this->getPage('Events List')->pressBatchCheckboxInRow();
    }

    /**
     * @Given /^I choose "([^"]*)" from batch action list and confirm it with "([^"]*)"$/
     */
    public function iChooseFromBatchActionListAndConfirmItWith($action, $button)
    {
        $this->getPage('Events list')->selectBatchAction($action);
        $this->getPage('Events list')->pressButton($button);
    }

    /**
     * @When /^I press "([^"]*)"$/
     */
    public function iPress($button)
    {
        $this->getPage('Events delete confirmation')->pressButton($button);
    }

    /**
     * @Then /^I should be redirected to "([^"]*)" page$/
     */
    public function iShouldBeRedirectedToPage($pageName)
    {
        expect($this->getPage($pageName)->isOpen())->toBe(true);
    }

    /**
     * @Given /^I should see (\d+) events on the list$/
     */
    public function iShouldSeeEventsOnTheList($count)
    {
        expect($this->getElement('Grid')->getRowsCount())->toBe((int) $count);
    }
}
