<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\DoctrineExtensionsBundle\Resolver\FSiFilePathResolver;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class TranslatableFSiRemovableFileExtension extends AbstractTranslatableExtension
{
    private $filePathResolver;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param TranslatableListener $translatableListener
     * @param PropertyAccessorInterface $propertyAccessor
     * @param FSiFilePathResolver $filePathResolver
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener,
        PropertyAccessorInterface $propertyAccessor,
        FSiFilePathResolver $filePathResolver
    ) {
        parent::__construct($managerRegistry, $translatableListener, $propertyAccessor);
        $this->filePathResolver = $filePathResolver;
    }

    /**
     * @inheritdoc
     */
    public function getExtendedType()
    {
        return 'fsi_removable_file';
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
