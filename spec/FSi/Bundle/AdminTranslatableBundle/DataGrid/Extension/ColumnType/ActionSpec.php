<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\DataGrid\Extension\ColumnType;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Routing\RouterInterface;

class ActionSpec extends ObjectBehavior
{
    public function let(LocaleManager $localeManager, RouterInterface $router): void
    {
        $this->beConstructedWith($localeManager, $router);
    }

    public function it_is_column_type_extension(): void
    {
        $this->shouldBeAnInstanceOf(ColumnAbstractTypeExtension::class);
    }

    public function it_extends_action_column(): void
    {
        $this->getExtendedColumnTypes()->shouldReturn(['action']);
    }
}
