<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\Form;

use FSi\Bundle\AdminTranslatableBundle\Form\TranslatableFormHelper;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\RemovableFileType;
use FSi\Bundle\DoctrineExtensionsBundle\Resolver\FSiFilePathResolver;
use FSi\DoctrineExtensions\Uploadable\File;
use PhpSpec\ObjectBehavior;
use stdClass;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use function expect;

class TranslatableFSiRemovableFileExtensionSpec extends ObjectBehavior
{
    public function let(
        TranslatableFormHelper $translatableFormHelper,
        FSiFilePathResolver $filePathResolver,
        FormInterface $form
    ): void {
        $translatableFormHelper->isFormPropertyPathTranslatable($form)->willReturn(false);
        $this->beConstructedWith($translatableFormHelper, $filePathResolver);
    }

    public function it_is_form_type_extension(): void
    {
        $this->shouldBeAnInstanceOf(AbstractTypeExtension::class);
    }

    public function it_extends_text_form(): void
    {
        $this->getExtendedTypes()->shouldContain(RemovableFileType::class);
    }

    public function it_does_nothing_if_form_has_no_property_path(
        FormView $view,
        FormInterface $form
    ): void {
        $form->getPropertyPath()->willReturn(null);

        $this->finishView($view, $form, []);
    }

    public function it_does_nothing_if_form_has_no_translatable_parent(
        FormView $view,
        FormInterface $form,
        FormConfigInterface $formConfig,
        FormInterface $parentForm,
        FormConfigInterface $parentFormConfig
    ): void {
        $form->getPropertyPath()->willReturn('translatable_property');
        $form->getConfig()->willReturn($formConfig);

        $form->getParent()->willReturn($parentForm);
        $parentForm->getConfig()->willReturn($parentFormConfig);
        $parentFormConfig->getInheritData()->willReturn(true);

        $parentForm->getParent()->willReturn(null);

        $this->finishView($view, $form, []);
    }

    public function it_does_nothing_if_forms_property_is_not_translatable_in_first_translatable_parent(
        FormView $view,
        FormInterface $form,
        FormInterface $parentForm,
        TranslatableFormHelper $translatableFormHelper
    ): void {
        $translatableFormHelper->getFirstTranslatableParent($form)->willReturn($parentForm);
        $translatableFormHelper->isFormPropertyPathTranslatable($form)->willReturn(false);

        $this->finishView($view, $form, []);

        expect($view->vars['translatable'])->toBe(false);
        expect($view->vars['not_translated'])->toBe(false);
    }

    public function it_sets_translatable_attribute_when_property_is_translatable(
        FormInterface $form,
        FormInterface $parentForm,
        TranslatableFormHelper $translatableFormHelper
    ): void {
        $view = new FormView();
        $fileView = new FormView($view);
        $view->children['translatable_property'] = $fileView;

        $translatableFormHelper->getFirstTranslatableParent($form)->willReturn($parentForm);
        $translatableFormHelper->isFormPropertyPathTranslatable($form)->willReturn(true);
        $translatableFormHelper->isFormDataInCurrentLocale($parentForm)->willReturn(false);

        $form->getName()->willReturn('translatable_property');

        $this->finishView($view, $form, []);

        expect($view->vars['translatable'])->toBe(true);
        expect($view->vars['not_translated'])->toBe(false);
    }

    public function it_sets_not_translated_attribute_when_property_no_translation(
        FSiFilePathResolver $filePathResolver,
        File $uploadableFile,
        FormInterface $form,
        FormInterface $parentForm,
        TranslatableFormHelper $translatableFormHelper
    ): void {
        $view = new FormView();
        $fileView = new FormView($view);
        $removeView = new FormView($view);
        $view->children['translatable_property'] = $fileView;
        $view->children['remove'] = $removeView;

        $translatableFormHelper->getFirstTranslatableParent($form)->willReturn($parentForm);
        $translatableFormHelper->isFormPropertyPathTranslatable($form)->willReturn(true);
        $translatableFormHelper->isFormDataInCurrentLocale($parentForm)->willReturn(false);
        $translatableFormHelper->getFormNormDataLocale($parentForm)->willReturn('en');

        $form->getName()->willReturn('translatable_property');

        $data = new stdClass();
        $view->vars['value'] = $data;
        $fileView->vars['value'] = $uploadableFile->getWrappedObject();
        $fileView->vars['data'] = $uploadableFile->getWrappedObject();

        $filePathResolver->fileBasename($uploadableFile->getWrappedObject())->willReturn('default-locale-filename');
        $filePathResolver->fileUrl($uploadableFile->getWrappedObject())->willReturn('default-locale-url');

        $this->finishView($view, $form, ['remove_name' => 'remove']);

        expect($view->vars['translatable'])->toBe(true);
        expect($view->vars['not_translated'])->toBe(true);
        expect($view->vars['label_attr']['data-default-locale'])->toBe('en');
        expect($view->vars['label_attr']['data-default-locale-value'])->toBe('default-locale-filename');
        expect($view->vars['label_attr']['data-default-locale-url'])->toBe('default-locale-url');
        expect($fileView->vars['value'])->toBe(null);
        expect($fileView->vars['data'])->toBe(null);
        expect($removeView->vars['checked'])->toBe(true);
    }
}
