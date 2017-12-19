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
    function let(
        LocaleManager $localeManager,
        RouterInterface $router
    ) {
        $this->beConstructedWith($localeManager, $router);
    }

    function it_is_column_type_extension()
    {
        $this->shouldBeAnInstanceOf(ColumnAbstractTypeExtension::class);
    }

    function it_extends_action_column()
    {
        $this->getExtendedColumnTypes()->shouldReturn(['action']);
    }
}
