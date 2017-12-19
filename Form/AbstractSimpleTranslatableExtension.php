<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Form;


use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

abstract class AbstractSimpleTranslatableExtension extends AbstractTranslatableExtension
{
    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     * @return bool
     */
    protected function hasCurrentValue(FormView $view, FormInterface $form, array $options): bool
    {
        return (bool) $view->vars['value'];
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    protected function moveCurrentValueToDefaultLocaleValue(
        FormView $view,
        FormInterface $form,
        array $options
    ): void {
        $view->vars['label_attr']['data-default-locale-value'] = $view->vars['value'];
        $view->vars['value'] = null;
    }
}
