<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Repository;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TranslatableMapBuilderSpec extends ObjectBehavior
{
    protected $resources = array(
        'text' => 'FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType',
        'integer' => 'FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\TypeIntegerType'
    );

    function let(LocaleManager $localeManager)
    {
        $this->beConstructedWith(__DIR__ . '/../../../../fixtures/resource_map.yml', $this->resources, $localeManager);
    }

    function it_should_throw_exception_when_translatable_option_is_not_boolean(LocaleManager $localeManager)
    {
        $this->shouldThrow('FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException')->during(
            '__construct',
            array(
                __DIR__ . '/../../../../fixtures/resource_map_with_invalid_value.yml',
                $this->resources,
                $localeManager
            )
        );
    }

    function it_should_return_translated_key_when_translatable_option_is_enabled(
        LocaleManager $localeManager
    ) {
        $localeManager->getLocales()->willReturn(array('pl', 'en'));
        $localeManager->getLocale()->willReturn('en');

        $this->getTranslatedKey('resource_group.resource_block.resource_a')
            ->shouldReturn('resource_group.resource_block.resource_a.en');
    }

    function it_should_return_original_key_when_translatable_option_is_disabled(
        LocaleManager $localeManager
    ) {
        $localeManager->getLocales()->willReturn(array('pl', 'en'));
        $localeManager->getLocale()->willReturn('en');

        $this->getTranslatedKey('resource_group.resource_block.resource_b')
            ->shouldReturn('resource_group.resource_block.resource_b');
    }

    function it_should_return_translatable_resource_when_translatable_option_is_enabled(
        LocaleManager $localeManager
    ) {
        $localeManager->getLocales()->willReturn(array('pl', 'en'));
        $localeManager->getLocale()->willReturn('en');

        $text = new TextType('resource_group.resource_block.resource_a.en');

        $this->beConstructedWith(__DIR__ . '/../../../../fixtures/resource_map.yml', $this->resources, $localeManager);
        $this->getResource('resource_group.resource_block.resource_a.en')->shouldBeLike($text);
    }
}
