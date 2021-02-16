<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element;

use Behat\Mink\Element\NodeElement;
use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\ElementNotFoundException;

class Grid extends Element
{
    protected $selector = ['css' => 'table.table-datagrid'];

    public function hasColumn(string $columnName): bool
    {
        return $this->has('css', sprintf('thead > tr > th > span > :contains("%s")', $columnName));
    }

    public function clickEdit(): void
    {
        $this->find('css', 'tbody tr td:nth-of-type(4)')->clickLink('Edit');
    }

    public function clickDisplay(): void
    {
        $this->find('css', 'tbody tr td:nth-of-type(4)')->clickLink('Display');
    }

    public function getRowsCount(): int
    {
        return count($this->findAll('css', 'tbody tr'));
    }

    public function getColumnPosition(string $columnTitle): int
    {
        $items = $this->findAll('css', 'thead th');

        foreach ($items as $i => $item) {
            /** @var $item NodeElement */
            $spanElement = $item->find('css', 'span');
            if (null !== $spanElement && $spanElement->getText() === $columnTitle) {
                return $i + 1;
            }
        }

        $availableColumns = array_map(static function (NodeElement $item) {
            return $item->getText();
        }, $items);

        throw new ElementNotFoundException(sprintf(
            'Unable to find column "%s". Available columns: %s',
            $columnTitle,
            implode(', ', $availableColumns)
        ));
    }

    public function getCell(int $rowPosition, int $columnPosition): NodeElement
    {
        $row = $this->find('xpath', sprintf('//tbody/tr[%d]', $rowPosition));
        if (null === $row) {
            throw new ElementNotFoundException(sprintf('Unable to find row %d', $rowPosition));
        }

        $cell = $row->find('xpath', sprintf('//td[%d]', $columnPosition));
        if (null === $cell) {
            throw new ElementNotFoundException(sprintf(
                'Unable to find cell %d in row %d',
                $columnPosition,
                $rowPosition
            ));
        }

        return $cell;
    }
}
