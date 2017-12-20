<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element\Grid;

class ListContext extends DefaultContext
{
    /**
     * @Given /^I click edit in "([^"]*)" column in third row$/
     */
    public function iClickEditInColumnInThirdRow(string $columnName)
    {
        /** @var Grid $grid */
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

        /** @var Grid $grid */
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
