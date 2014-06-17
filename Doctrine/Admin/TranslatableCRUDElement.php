<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement as BaseCRUD;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class TranslatableCRUDElement extends BaseCRUD implements TranslatableAwareInterface
{
    protected $localeManager;

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->replaceDefaults(array(
            'template_crud_list' => '@FSiAdminTranslatable/CRUD/list.html.twig'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_translatable_crud_list';
    }

    public function setLocaleManager(LocaleManager $localeManager)
    {
        $this->localeManager = $localeManager;
    }

    public function getLocale()
    {
        return $this->localeManager->getLocale();
    }
}
