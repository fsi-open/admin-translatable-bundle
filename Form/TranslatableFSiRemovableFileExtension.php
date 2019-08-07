<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\RemovableFileType;
use FSi\Bundle\DoctrineExtensionsBundle\Resolver\FSiFilePathResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class TranslatableFSiRemovableFileExtension extends AbstractTranslatableExtension
{
    /**
     * @var FSiFilePathResolver
     */
    private $filePathResolver;

    public function __construct(
        TranslatableFormHelper $translatableFormHelper,
        FSiFilePathResolver $filePathResolver
    ) {
        parent::__construct($translatableFormHelper);
        $this->filePathResolver = $filePathResolver;
    }

    public static function getExtendedTypes()
    {
        return [RemovableFileType::class];
    }

    public function getExtendedType()
    {
        return RemovableFileType::class;
    }

    protected function hasCurrentValue(FormView $view, FormInterface $form, array $options): bool
    {
        return isset($view[$form->getName()]->vars['data']);
    }

    protected function moveCurrentValueToDefaultLocaleValue(
        FormView $view,
        FormInterface $form,
        array $options
    ): void {
        $file = $view[$form->getName()]->vars['data'];
        $view->vars['label_attr']['data-default-locale-value'] = $this->filePathResolver->fileBasename($file);
        $view->vars['label_attr']['data-default-locale-url'] = $this->filePathResolver->fileUrl($file);

        $view[$form->getName()]->vars['value'] = null;
        $view[$form->getName()]->vars['data'] = null;

        $view[$options['remove_name']]->vars['checked'] = true;
    }
}
