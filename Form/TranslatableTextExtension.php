<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use FSi\Bundle\AdminTranslatableBundle\Form\TypeSolver;

class TranslatableTextExtension extends AbstractSimpleTranslatableExtension
{
    /**
     * @inheritdoc
     */
    public function getExtendedType()
    {
        return TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\TextType', 'text');
    }
}
