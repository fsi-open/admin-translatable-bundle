<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Repository;

use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\FSi\Bundle\ResourceRepositoryBundle\Entity\Resource;

class TranslatableMapBuilderSpec extends ObjectBehavior
{
    protected $resources = array(
        'text' => 'FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType',
        'integer' => 'FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\TypeIntegerType'
    );

    function let(
        TranslatableListener $translatableListener
    ) {
        $this->beConstructedWith(
            __DIR__ . '/../../../../fixtures/resource_map.yml',
            $this->resources,
            $translatableListener
        );
    }

    function it_should_throw_exception_when_translatable_option_is_not_boolean(
        TranslatableListener $translatableListener
    ) {
        $this->shouldThrow('FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException')->during(
            '__construct',
            array(
                __DIR__ . '/../../../../fixtures/resource_map_with_invalid_value.yml',
                $this->resources,
                $translatableListener
            )
        );
    }

    function it_should_return_translatable_resource_when_translatable_option_is_enabled(
        TranslatableListener $translatableListener,
        Resource $resource
    ) {
        $translatableListener->getLocale()->willReturn('en');

        $this->getResource('resource_group.resource_block.resource_a')
            ->willReturn($resource);

        $resource->getKey()
            ->shouldReturn('resource_group.resource_block.resource_a.en');
    }

    function it_should_return_original_resource_when_translatable_option_is_disabled(
        TranslatableListener $translatableListener,
        Resource $resource
    ) {
        $translatableListener->getLocale()->willReturn('en');

        $this->getResource('resource_group.resource_block.resource_b')
            ->willReturn($resource);

        $resource->getKey()
            ->shouldReturn('resource_group.resource_block.resource_b');
    }

    function it_should_return_map_in_current_locale(
        TranslatableListener $translatableListener
    ) {
        $translatableListener->getLocale()->willReturn('en');

        $map = array(
            'resource_group' =>
                array(
                    'resource_block' =>
                        array(
                            'resource_a' => new TextType('resource_group.resource_block.resource_a.en'),
                            'resource_b' => new TextType('resource_group.resource_block.resource_b'),
                        )
                )
        );

        $this->getMap()
            ->shouldReturn($map);
    }
}
