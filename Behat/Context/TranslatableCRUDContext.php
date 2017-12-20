<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function iShouldSeeSimpleTextFilter(string $filterName)
    {
        expect($this->getElement('Filters')->hasField($filterName))->toBe(true);
    }

    /**
     * @Given /^I fill simple text filter "([^"]*)" with value "([^"]*)"$/
     */
    public function iFillSimpleTextFilterWithValue(string $filterName, $filterValue)
    {
        $this->getElement('Filters')->fillField($filterName, $filterValue);
    }

    /**
     * @Given /^simple text filter "([^"]*)" should be filled with value "([^"]*)"$/
     */
    public function simpleTextFilterShouldBeFilledWithValue(string $filterName, $filterValue)
    {
        expect($this->getElement('Filters')->findField($filterName)->getValue())->toBe($filterValue);
    }

    /**
     * @Given /^I edit first event on the list$/
     */
    public function iEditFirstEventOnTheList()
    {
        if ($this->isSeleniumDriver()) {
            $this->waitUntilObjectVisible('//td[contains(., "Edit")]', true);
        }

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
    public function iChangeFieldValueTo(string $field, $value)
    {
        $this->getElement('Form')->fillField($field, $value);
    }

    /**
     * @Given /^I press "([^"]*)" button$/
     */
    public function iPressButton(string $button)
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
    public function iChooseFromBatchActionListAndConfirmItWith(string $action, string $button)
    {
        $this->getPage('Events list')->selectBatchAction($action);
        $this->getPage('Events list')->pressButton($button);
    }

    /**
     * @When /^I press "([^"]*)"$/
     */
    public function iPress(string $button)
    {
        $this->getPage('Events delete confirmation')->pressButton($button);
    }

    /**
     * @Then /^I should be redirected to "([^"]*)" page with locale "([^"]*)"$/
     */
    public function iShouldBeRedirectedToPage(string $pageName, string $locale)
    {
        expect($this->getPage($pageName)->isOpen(['locale' => $locale]))->toBe(true);
    }

    /**
     * @Given /^I should see (\d+) events on the list$/
     */
    public function iShouldSeeEventsOnTheList(int $count)
    {
        expect($this->getElement('Grid')->getRowsCount())->toBe((int) $count);
    }
}
