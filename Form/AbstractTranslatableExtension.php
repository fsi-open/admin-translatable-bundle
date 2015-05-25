<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

abstract class AbstractTranslatableExtension extends AbstractTypeExtension
{
    /**
     * @var TranslatableFormHelper
     */
    protected $translatableFormHelper;

    /**
     * @param TranslatableFormHelper $translatableFormHelper
     */
    public function __construct(TranslatableFormHelper $translatableFormHelper)
    {
        $this->translatableFormHelper = $translatableFormHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['translatable'] = false;
        $view->vars['not_translated'] = false;

        if (!$this->translatableFormHelper->isFormForTranslatableProperty($form)) {
            return;
        }

        $parent = $this->translatableFormHelper->getFirstTranslatableParent($form);
        if (!$parent) {
            return;
        }

        $view->vars['translatable'] = true;
        if (!$this->hasCurrentValue($view, $form, $options) ||
            $this->translatableFormHelper->isFormDataInCurrentLocale($parent)) {
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
    abstract protected function hasCurrentValue(FormView $view, FormInterface $form, array $options);

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    abstract protected function moveCurrentValueToDefaultLocaleValue(FormView $view, FormInterface $form, array $options);
}
