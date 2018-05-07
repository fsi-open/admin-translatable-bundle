<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\AdminTranslatableBundle\Form\TypeSolver;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\FixturesBundle\Entity\Event as EventEntity;
use FSi\FixturesBundle\Form\CommentType;
use FSi\FixturesBundle\Form\FilesType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @Admin\Element
 */
class Event extends TranslatableCRUDElement
{
    /**
     * {@inheritDoc}
     */
    public function getId(): string
    {
        return 'admin_event';
    }

    /**
     * {@inheritDoc}
     */
    public function getClassName(): string
    {
        return 'FSi\FixturesBundle\Entity\Event';
    }

    /**
     * {@inheritDoc}
     */
    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
        $datagrid = $factory->createDataGrid($this->getId());

        $datagrid->addColumn('name', 'text', [
            'label' => 'admin.events.grid.name',
            'editable' => true,
        ]);

        $datagrid->addColumn('agreement', 'fsi_file', [
            'label' => 'admin.events.grid.agreement'
        ]);

        $datagrid->addColumn('actions', 'action', [
            'label' => 'admin.grid.actions',
            'field_mapping' => ['id'],
            'actions' => [
                'edit' => [
                    'route_name' => 'fsi_admin_translatable_form',
                    'additional_parameters' => ['element' => $this->getId()],
                    'parameters_field_mapping' => ['id' => 'id']
                ],
                'display' => [
                    'route_name' => 'fsi_admin_translatable_display',
                    'additional_parameters' => ['element' => 'admin_event_preview'],
                    'parameters_field_mapping' => ['id' => 'id']
                ],
            ]
        ]);

        return $datagrid;
    }

    /**
     * {@inheritDoc}
     */
    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
        $qb = $this->getRepository()->createTranslatableQueryBuilder('e', 't', 'dt');

        $datasource = $factory->createDataSource('doctrine-orm', ['qb' => $qb], $this->getId());

        $datasource->addField('name', 'text', 'like', [
            'field' => 't.name'
        ]);

        return $datasource;
    }

    /**
     * {@inheritDoc}
     */
    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        if (!$data) {
            $data = new EventEntity();
        }
        $formType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form');
        $textType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\TextType', 'text');
        $ckeditorType = TypeSolver::getFormType('FOS\CKEditorBundle\Form\Type\CKEditorType', 'ckeditor');
        $collectionType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\CollectionType', 'collection');
        $removableFileType = TypeSolver::getFormType(
            'FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\RemovableFileType',
            'fsi_removable_file'
        );
        $commentType = TypeSolver::getFormType('FSi\FixturesBundle\Form\CommentType', new CommentType());
        $filesType = TypeSolver::getFormType('FSi\FixturesBundle\Form\FilesType', new FilesType());

        $form = $factory->create($formType, $data, ['data_class' => $this->getClassName()]);

        $form->add('name', $textType, ['label' => 'admin.events.form.name']);

        $form->add('agreement', $removableFileType, ['required' => false]);

        $form->add('description', $ckeditorType, ['required' => false]);

        $entryTypeLabel = TypeSolver::isSymfony3FormNamingConvention()? 'entry_type' : 'type';
        $form->add('comments', $collectionType, [
            $entryTypeLabel => $commentType,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ]);

        $form->add('files', $collectionType, [
            $entryTypeLabel => $filesType,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ]);

        return $form;
    }
}
