<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class TranslatableTextExtension extends AbstractSimpleTranslatableExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [TextType::class];
    }
}
