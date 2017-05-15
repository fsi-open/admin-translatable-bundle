<?php

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
    public static function getFormType($fqcnType, $shortType)
    {
        return self::isSymfony3FormNamingConvention() ? $fqcnType : $shortType;
    }

    /**
     * @return bool
     */
    public static function isSymfony3FormNamingConvention()
    {
        return !method_exists('Symfony\Component\Form\FormTypeInterface', 'getName');
    }
}
