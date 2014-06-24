<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Knp\Menu\MenuFactory;
use Knp\Menu\MenuItem;
use PhpSpec\ObjectBehavior;

class MenuBuilderSpec extends ObjectBehavior
{
    function let(
        MenuFactory $menuFactory,
        Manager $manager,
        LocaleManager $localeManager
    ) {
        $this->beConstructedWith($menuFactory, $manager, $localeManager);
    }

    function it_extends_admin_bundle_menu_builder()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Menu\MenuBuilder');
    }

    function it_create_menu_with_translatable_elements(
        MenuFactory $menuFactory,
        Manager $manager,
        MenuItem $root,
        MenuItem $menuItem,
        TranslatableCRUDElement $element,
        LocaleManager $localeManager
    ) {
        $menuFactory->createItem('root')->willReturn($root);
        $root->setChildrenAttribute('class', 'nav navbar-nav')->shouldBeCalled();
        $root->setChildrenAttribute('id', 'top-menu')->shouldBeCalled();

        $manager->getElementsWithoutGroup()->willReturn(array($element));
        $manager->getGroups()->willReturn(array());

        $element->getName()->willReturn('translatable_element_name');
        $element->getRoute()->willReturn('fsi_translatable_admin_route');
        $element->getRouteParameters()->willReturn(array());
        $element->hasOption('menu')->willReturn(true);
        $element->getOption('menu')->willReturn(true);

        $localeManager->getLocale()->willReturn('en');

        $root->addChild('translatable_element_name', array(
            'route' => 'fsi_translatable_admin_route',
            'routeParameters' => array('locale' => 'en'),
        ))->shouldBeCalled();
        $root->offsetGet('translatable_element_name')->willReturn($menuItem);
        $menuItem->setAttribute('class', 'admin-element')->shouldBeCalled();

        $this->createMenu()->shouldReturn($root);
    }
}
