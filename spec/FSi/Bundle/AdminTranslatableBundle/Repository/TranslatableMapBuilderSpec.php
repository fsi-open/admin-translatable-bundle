<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\Repository;

use FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\IntegerType;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use PhpSpec\ObjectBehavior;

class TranslatableMapBuilderSpec extends ObjectBehavior
{
    protected $resources = ['text' => TextType::class, 'integer' => IntegerType::class];

    public function let(TranslatableListener $translatableListener): void
    {
        $this->beConstructedWith(
            __DIR__ . '/../../../../fixtures/resource_map.yml',
            $this->resources,
            $translatableListener
        );
    }

    public function it_should_throw_exception_when_translatable_option_is_not_boolean(
        TranslatableListener $translatableListener
    ): void {
        $this->shouldThrow(ConfigurationException::class)->during(
            '__construct',
            [
                __DIR__ . '/../../../../fixtures/resource_map_with_invalid_value.yml',
                $this->resources,
                $translatableListener
            ]
        );
    }

    public function it_should_return_translatable_resource_when_translatable_option_is_enabled(
        TranslatableListener $translatableListener
    ): void {
        $translatableListener->getLocale()->willReturn('en');
        $resource = new TextType('resource_group.resource_block.resource_a.en');
        $this->getResource('resource_group.resource_block.resource_a')->shouldBeLike($resource);
    }

    public function it_should_return_original_resource_when_translatable_option_is_disabled(
        TranslatableListener $translatableListener
    ): void {
        $translatableListener->getLocale()->willReturn('en');
        $resource = new TextType('resource_group.resource_block.resource_b');
        $this->getResource('resource_group.resource_block.resource_b')->shouldBeLike($resource);
    }

    public function it_should_return_map_in_current_locale(
        TranslatableListener $translatableListener
    ): void {
        $translatableListener->getLocale()->willReturn('en');

        $map = $this->getMap();

        $map['resource_group']['resource_block']['resource_a']->shouldHaveType(TextType::class);

        $map['resource_group']['resource_block']['resource_a']->getName()
            ->shouldReturn('resource_group.resource_block.resource_a.en');

        $map['resource_group']['resource_block']['resource_b']->shouldHaveType(TextType::class);

        $map['resource_group']['resource_block']['resource_b']->getName()
            ->shouldReturn('resource_group.resource_block.resource_b');
    }
}
