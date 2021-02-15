<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

abstract class AbstractTranslatableExtension extends AbstractTypeExtension
{
    /**
     * @var TranslatableFormHelper
     */
    protected $translatableFormHelper;

    public function __construct(TranslatableFormHelper $translatableFormHelper)
    {
        $this->translatableFormHelper = $translatableFormHelper;
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['translatable'] = false;
        $view->vars['not_translated'] = false;

        if (false === $this->translatableFormHelper->isFormPropertyPathTranslatable($form)) {
            return;
        }

        $parent = $this->translatableFormHelper->getFirstTranslatableParent($form);
        if (null === $parent) {
            return;
        }

        $view->vars['translatable'] = true;
        if (
            false === $this->hasCurrentValue($view, $form, $options)
            || true === $this->translatableFormHelper->isFormDataInCurrentLocale($parent)
        ) {
            return;
        }

        $view->vars['not_translated'] = true;
        $view->vars['label_attr']['data-default-locale'] =
            $this->translatableFormHelper->getFormNormDataLocale($parent);

        $this->moveCurrentValueToDefaultLocaleValue($view, $form, $options);
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     * @return bool
     */
    abstract protected function hasCurrentValue(FormView $view, FormInterface $form, array $options): bool;

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    abstract protected function moveCurrentValueToDefaultLocaleValue(
        FormView $view,
        FormInterface $form,
        array $options
    ): void;
}
