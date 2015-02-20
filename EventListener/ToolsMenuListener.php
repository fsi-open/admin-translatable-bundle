<?php

namespace FSi\Bundle\AdminTranslatableBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

class ToolsMenuListener
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LocaleManager
     */
    private $localeManager;

    /**
     * @var Request
     */
    private $request;

    public function __construct(TranslatorInterface $translator, LocaleManager $localeManager, RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->localeManager = $localeManager;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function createTranslationLocaleMenu(MenuEvent $event)
    {
        $translation = $this->createRootItem();
        $event->getMenu()->addChild($translation);

        if (!$this->isRequestTranslatable()) {
            return;
        }

        $this->populateTranslationLocaleMenu($translation);
    }

    private function isRequestTranslatable()
    {
        return array_key_exists('locale', $this->getRequestParameters());
    }

    private function getRequestParameters()
    {
        $query = $this->request->query->all();

        if (isset($query['redirect_uri'])) {
            unset($query['redirect_uri']);
        }

        return array_merge(
            $this->request->get('_route_params'),
            $query
        );
    }

    /**
     * @return Item
     */
    private function createRootItem()
    {
        $translation = new Item('translation-locale');

        $translation->setLabel(
            $this->translator->trans(
                'admin.locale.dropdown.title',
                array('%locale%' => $this->localeManager->getLocale()),
                'FSiAdminTranslatableBundle'
            )
        );

        $translation->setOptions(array(
            'attr' => array(
                'id' => 'translatable-switcher',
            )
        ));

        return $translation;
    }

    private function populateTranslationLocaleMenu(Item $menu)
    {
        $requestParameters = $this->getRequestParameters();
        $route = $this->request->get('_route');

        foreach ($this->localeManager->getLocales() as $locale) {
            $requestParameters['locale'] = $locale;

            $localeItem = new RoutableItem(sprintf('translation-locale.%s', $locale), $route, $requestParameters);
            $localeItem->setLabel($locale);

            if ($locale == $this->localeManager->getLocale()) {
                $localeItem->setOptions(array('attr' => array('class' => 'active')));
            }

            $menu->addChild($localeItem);
        }
    }
}
