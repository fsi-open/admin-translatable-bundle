<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\EventListener;

use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableAwareInterface;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class TranslatableCRUDListener implements EventSubscriberInterface
{
    /**
     * @var \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager
     */
    protected $localeManager;

    /**
     * @var \Symfony\Bridge\Doctrine\ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    protected $propertyAccessor;

    /**
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     * @param \Symfony\Bridge\Doctrine\ManagerRegistry $managerRegistry
     * @param \Symfony\Component\PropertyAccess\PropertyAccessor $propertyAccessor
     */
    public function __construct(
        LocaleManager $localeManager,
        ManagerRegistry $managerRegistry,
        PropertyAccessor $propertyAccessor
    ) {
        $this->localeManager = $localeManager;
        $this->managerRegistry = $managerRegistry;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array (
            CRUDEvents::CRUD_EDIT_ENTITY_PRE_SAVE => 'setFormDataLocale',
        );
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     */
    public function setFormDataLocale(FormEvent $event)
    {
        $element = $event->getElement();

        if ($element instanceof TranslatableAwareInterface) {
            $entity = $event->getForm()->getData();
            $metadata = $this->getTranslatableListener()
                ->getExtendedMetadata(
                    $this->getObjectManager($event),
                    $this->getFormDataClass($event)
                );

            $this->propertyAccessor->setValue(
                $entity,
                $metadata->localeProperty,
                $this->getLocale()
            );
        }
    }

    /**
     * @return string
     */
    private function getLocale()
    {
        return $this->localeManager->getLocale();
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @return string
     */
    private function getFormDataClass(FormEvent $event)
    {
        return $event->getForm()->getConfig()->getDataClass();
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    private function getObjectManager(FormEvent $event)
    {
        return $this->managerRegistry->getManagerForClass($this->getFormDataClass($event));
    }

    /**
     * @return \FSi\DoctrineExtensions\Translatable\TranslatableListener|null
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    private function getTranslatableListener()
    {
        $evm = $this->managerRegistry->getManager()->getEventManager();
        foreach ($evm->getListeners() as $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof TranslatableListener) {
                    return $listener;
                }
            }
        }

        throw new RuntimeException('Translatable extension is not enabled in "fsi_doctrine_extensions" section of "config.yml"');
    }
}
