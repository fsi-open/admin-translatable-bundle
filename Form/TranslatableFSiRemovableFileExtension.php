<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use FSi\Bundle\AdminTranslatableBundle\Form\TypeSolver;
use FSi\Bundle\DoctrineExtensionsBundle\Resolver\FSiFilePathResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class TranslatableFSiRemovableFileExtension extends AbstractTranslatableExtension
{
    private $filePathResolver;

    /**
     * @param TranslatableFormHelper $translatableFormHelper
     * @param FSiFilePathResolver $filePathResolver
     */
    public function __construct(
        TranslatableFormHelper $translatableFormHelper,
        FSiFilePathResolver $filePathResolver
    ) {
        parent::__construct($translatableFormHelper);
        $this->filePathResolver = $filePathResolver;
    }

    /**
     * @inheritdoc
     */
    public function getExtendedType()
    {
        return TypeSolver::getFormType(
            'FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\RemovableFileType',
            'fsi_removable_file'
        );
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     * @return bool
     */
    protected function hasCurrentValue(FormView $view, FormInterface $form, array $options)
    {
        return isset($view[$form->getName()]->vars['data']);
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    protected function moveCurrentValueToDefaultLocaleValue(FormView $view, FormInterface $form, array $options)
    {
        $file = $view[$form->getName()]->vars['data'];
        $view->vars['label_attr']['data-default-locale-value'] = $this->filePathResolver->fileBasename($file);
        $view->vars['label_attr']['data-default-locale-url'] = $this->filePathResolver->fileUrl($file);

        $view[$form->getName()]->vars['value'] = null;
        $view[$form->getName()]->vars['data'] = null;

        $view[$options['remove_name']]->vars['checked'] = true;
    }
}
