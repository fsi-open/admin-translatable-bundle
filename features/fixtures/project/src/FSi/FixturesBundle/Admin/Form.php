<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableFormElement;
use FSi\Bundle\AdminTranslatableBundle\Form\TypeSolver;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @Admin\Element
 */
class Form extends TranslatableFormElement
{
    public function getId(): string
    {
        return 'admin_form';
    }

    public function getClassName(): string
    {
        return 'FSi\FixturesBundle\Entity\Comment';
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        $formType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form');
        $textareaType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\TextareaType', 'textarea');

        $form = $factory->create($formType, $data, ['data_class' => $this->getClassName()]);
        $form->add('text', $textareaType);

        return $form;
    }
}
