<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;

class TranslatableCRUDContext extends DefaultContext
{
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
     * @Then /^I should be redirected to "([^"]*)" page with locale "([^"]*)"$/
     */
    public function iShouldBeRedirectedToPage($pageName, $locale)
    {
        expect($this->getPage($pageName)->isOpen(['locale' => $locale]))->toBe(true);
    }

    /**
     * @Given /^I should see (\d+) events on the list$/
     */
    public function iShouldSeeEventsOnTheList($count)
    {
        expect($this->getElement('Grid')->getRowsCount())->toBe((int) $count);
    }
}
