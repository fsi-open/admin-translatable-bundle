<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableFormElement;
use FSi\Bundle\AdminTranslatableBundle\Form\TypeSolver;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @Admin\Element
 */
class Form extends TranslatableFormElement
{
    public function getId()
    {
        return 'admin_form';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Comment';
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $formType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form');
        $textareaType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\TextareaType', 'textarea');

        $form = $factory->create($formType, $data, ['data_class' => $this->getClassName()]);
        $form->add('text', $textareaType);

        return $form;
    }
}
