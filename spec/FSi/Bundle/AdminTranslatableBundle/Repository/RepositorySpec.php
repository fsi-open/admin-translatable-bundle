<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Repository;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Bundle\AdminTranslatableBundle\Repository\TranslatableMapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Entity\ResourceRepository;
use FSi\Bundle\ResourceRepositoryBundle\Model\Resource;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceEntity extends Resource
{
}

class RepositorySpec extends ObjectBehavior
{
    function let(TranslatableMapBuilder $builder, ResourceRepository $repository)
    {
        $this->beConstructedWith($builder, $repository);
    }

    function it_return_translatable_resource_key(
        TranslatableMapBuilder $builder,
        ResourceRepository $repository,
        TextType $resource,
        ResourceEntity $entity,
        LocaleManager $localeManager
    ) {
        $entity->getTextValue()->shouldBeCalled()->willReturn('resource en');
        $localeManager->getLocale()->willReturn('en');
        $builder->getRealKey('resources_group.resource_a')->willReturn('resources_group.resource_a.en');
        $repository->get('resources_group.resource_a.en')->shouldBeCalled()->willReturn($entity);
        $builder->getResource(Argument::type('string'))->shouldBeCalled()->willReturn($resource);
        $resource->getResourceProperty()->shouldBeCalled()->willReturn('textValue');

        $this->get('resources_group.resource_a')->shouldReturn('resource en');
    }
}
