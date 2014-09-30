<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Repository;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Bundle\AdminTranslatableBundle\Repository\TranslatableMapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Model\Resource;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ResourceEntity extends Resource
{
}

class RepositorySpec extends ObjectBehavior
{
    function let(TranslatableMapBuilder $builder, ResourceValueRepository $repository)
    {
        $this->beConstructedWith($builder, $repository, '');
    }

    function it_return_translatable_resource_key(
        TranslatableMapBuilder $builder,
        ResourceValueRepository $repository,
        TextType $resource,
        ResourceEntity $entity,
        LocaleManager $localeManager
    ) {
        $entity->getTextValue()->shouldBeCalled()->willReturn('resource en');
        $localeManager->getLocale()->willReturn('en');
        $builder->getTranslatedKey('resources_group.resource_a')->willReturn('resources_group.resource_a.en');
        $repository->get('resources_group.resource_a.en')->shouldBeCalled()->willReturn($entity);
        $builder->getResource(Argument::type('string'))->shouldBeCalled()->willReturn($resource);
        $resource->getResourceProperty()->shouldBeCalled()->willReturn('textValue');

        $this->get('resources_group.resource_a')->shouldReturn('resource en');
    }

    function it_set_resource_value_with_translatable_resource_key(
        TranslatableMapBuilder $builder,
        ResourceValueRepository $repository,
        TextType $resource,
        ResourceEntity $entity,
        LocaleManager $localeManager
    ) {
        $localeManager->getLocale()->willReturn('en');
        $builder->getTranslatedKey('resources_group.resource_a')->willReturn('resources_group.resource_a.en');
        $repository->get('resources_group.resource_a.en')->willReturn($entity);
        $builder->getResource(Argument::type('string'))->willReturn($resource);
        $resource->getResourceProperty()->willReturn('textValue');
        $entity->setTextValue('resource en')->shouldBeCalled();
        $repository->save($entity)->shouldBeCalled();

        $this->set('resources_group.resource_a', 'resource en');
    }
}
