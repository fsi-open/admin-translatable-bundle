<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableFormElement;
use FSi\FixturesBundle\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class Form extends TranslatableFormElement
{
    public function getId(): string
    {
        return 'admin_form';
    }

    public function getClassName(): string
    {
        return Comment::class;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        $form = $factory->create(FormType::class, $data, ['data_class' => $this->getClassName()]);
        $form->add('text', TextareaType::class);

        return $form;
    }
}
