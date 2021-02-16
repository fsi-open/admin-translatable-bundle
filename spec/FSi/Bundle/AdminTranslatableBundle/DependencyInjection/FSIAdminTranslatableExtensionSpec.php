<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class FSIAdminTranslatableExtensionSpec extends ObjectBehavior
{
    public function it_is_bundle_extension(): void
    {
        $this->shouldBeAnInstanceOf(Extension::class);
    }
}
