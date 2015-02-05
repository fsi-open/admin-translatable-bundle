<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableFormElement;
use FSi\FixturesBundle\Form\CommentType;
use Symfony\Component\Form\FormFactoryInterface;
use FSi\Bundle\AdminBundle\Annotation as Admin;

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
        $form = $factory->create('form', $data, array(
            'data_class' => $this->getClassName(),
        ));

        $form->add('text', 'textarea');

        return $form;
    }
}
