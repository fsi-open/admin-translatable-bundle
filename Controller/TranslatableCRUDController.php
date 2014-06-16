<?php

namespace FSi\Bundle\AdminTranslatableBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use Symfony\Component\HttpFoundation\Request;
use FSi\Bundle\AdminBundle\Controller\CRUDController as BaseController;

class TranslatableCRUDController extends BaseController
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(AbstractCRUD $element, Request $request)
    {
        return $this->action($element, $request, 'fsi_admin_translatable_crud_list', $this->listActionTemplate);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(AbstractCRUD $element, Request $request)
    {
        return $this->action($element, $request, 'fsi_admin_translatable_crud_create', $this->createActionTemplate);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(AbstractCRUD $element, Request $request)
    {
        return $this->action($element, $request, 'fsi_admin_translatable_crud_edit', $this->editActionTemplate);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(AbstractCRUD $element, Request $request)
    {
       return $this->action($element, $request, 'fsi_admin_translatable_crud_delete', $this->deleteActionTemplate);
    }
}
