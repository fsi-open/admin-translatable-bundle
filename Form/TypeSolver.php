<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use Symfony\Component\Form\FormTypeInterface;

/**
 * @internal
 */
final class TypeSolver
{
    /**
     * Return FQCN form type or old style form type
     *
     * @param string $fqcnType
     * @param string|FormTypeInterface $shortType
     * @return string|FormTypeInterface
     */
    public static function getFormType(string $fqcnType, $shortType)
    {
        return self::isSymfony3FormNamingConvention() ? $fqcnType : $shortType;
    }

    /**
     * @return bool
     */
    public static function isSymfony3FormNamingConvention(): bool
    {
        return !method_exists(FormTypeInterface::class, 'getName');
    }
}
