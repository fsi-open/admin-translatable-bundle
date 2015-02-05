<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;

class ListContext extends PageObjectContext
{
    /**
     * @Given /^I should see list$/
     */
    public function iShouldSeeList(TableNode $table)
    {
        /** @var \FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element\Grid $grid */
        $grid = $this->getElement('Grid');

        foreach ($table->getHash() as $i => $row) {
            $columnName = key($row);
            $expectedCellValue = $row[$columnName];

            $columnPosition = $grid->getColumnPosition($columnName);
            $actualCell = $grid->getCell($i + 1, $columnPosition);

            expect($actualCell->getText())->toBe($expectedCellValue);
        }
    }
}
