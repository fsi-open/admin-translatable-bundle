<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element;

use Behat\Mink\Element\NodeElement;
use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\ElementNotFoundException;

class Grid extends Element
{
    protected $selector = ['css' => 'table.table-datagrid'];

    public function hasColumn($columnName)
    {
        return $this->has('css', sprintf('thead > tr > th > span > :contains("%s")', $columnName));
    }

    public function clickEdit()
    {
        $this->find('css', 'tbody tr td:nth-of-type(4)')->clickLink('Edit');
    }

    public function clickDisplay()
    {
        $this->find('css', 'tbody tr td:nth-of-type(4)')->clickLink('Display');
    }

    public function getRowsCount()
    {
        return count($this->findAll('css', 'tbody tr'));
    }

    public function getColumnPosition($columnTitle)
    {
        $items = $this->findAll('css', 'thead th');

        foreach ($items as $i => $item) {
            /** @var $item NodeElement */
            $spanElement = $item->find('css', 'span');
            if ($spanElement && $columnTitle === $spanElement->getText()) {
                return $i + 1;
            }
        }

        $availableColumns = array_map(function (NodeElement $item) { return $item->getText(); }, $items);

        throw new ElementNotFoundException(
            sprintf('Unable to find column "%s". Available columns: %s', $columnTitle, join(', ', $availableColumns))
        );
    }

    /**
     * @param $rowPosition
     * @param $columnPosition
     * @return NodeElement
     * @throws BehaviorException
     */
    public function getCell($rowPosition, $columnPosition)
    {
        $row = $this->find('xpath', sprintf('//tbody/tr[%d]', $rowPosition));
        if (null === $row) {
            throw new ElementNotFoundException(sprintf('Unable to find row %d', $rowPosition));
        }

        $cell = $row->find('xpath', sprintf('//td[%d]', $columnPosition));
        if (null === $cell) {
            throw new ElementNotFoundException(sprintf('Unable to find cell %d in row %d', $columnPosition, $rowPosition));
        }

        return $cell;
    }
}
