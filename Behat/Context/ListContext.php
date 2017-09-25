<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;

class ListContext extends DefaultContext
{
    /**
     * @Given /^I click edit in "([^"]*)" column in third row$/
     */
    public function iClickEditInColumnInThirdRow($columnName)
    {
        /** @var \FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element\Grid $grid */
        $grid = $this->getElement('Grid');
        $cell = $grid->getCell(3, $grid->getColumnPosition($columnName));
        $grid->getSession()->getDriver()->click($cell->getXpath());
        $cell->find('css', 'a')->click();
    }

    /**
     * @Then /^I should see following list$/
     */
    public function iShouldSeeFollowingList(TableNode $table)
    {
        if ($this->isSeleniumDriver()) {
            $this->waitUntilObjectVisible('table.table-datagrid');
        }

        /** @var \FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element\Grid $grid */
        $grid = $this->getElement('Grid');

        expect(count($table->getHash()))->toBe($grid->getRowsCount());

        $rowNumber = 1;
        foreach ($table->getHash() as $row) {
            foreach ($row as $columnName => $cellValue) {
                $cell = $grid->getCell($rowNumber, $grid->getColumnPosition($columnName));

                expect($cell->getText())->toBe($cellValue);
            }

            $rowNumber++;
        }
    }
}
