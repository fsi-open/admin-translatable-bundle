<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element;

use Behat\Behat\Exception\BehaviorException;
use Behat\Mink\Element\NodeElement;
use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class Grid extends Element
{
    protected $selector = array('css' => 'table.table-datagrid');

    public function hasColumn($columnName)
    {
        return $this->has('css', sprintf('thead > tr > th > span > :contains("%s")', $columnName));
    }

    public function clickEdit()
    {
        $this->find('css', 'tbody tr td:nth-of-type(3)')->clickLink('Edit');
    }

    public function clickDisplay()
    {
        $this->find('css', 'tbody tr td:nth-of-type(3)')->clickLink('preview');
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

        throw new BehaviorException(
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
            throw new BehaviorException(sprintf('Unable to find row %d', $rowPosition));
        }

        $cell = $row->find('xpath', sprintf('//td[%d]', $columnPosition));
        if (null === $cell) {
            throw new BehaviorException(sprintf('Unable to find cell %d in row %d', $columnPosition, $rowPosition));
        }

        return $cell;
    }
}
