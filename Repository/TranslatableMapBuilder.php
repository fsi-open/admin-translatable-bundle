<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Repository;

use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder as BaseMapBuilder;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\PropertyAccess\PropertyAccess;

class TranslatableMapBuilder extends BaseMapBuilder
{
    /**
     * @var TranslatableListener
     */
    protected $translatableListener;

    /**
     * @var array
     */
    protected $translatedKeys;

    /**
     * @var string
     */
    protected $mapPath;

    public function __construct(
        string $mapPath,
        array $resourceTypes,
        TranslatableListener $translatableListener
    ) {
        $this->mapPath = $mapPath;
        $this->translatableListener = $translatableListener;
        $this->translatedKeys = [];

        $this->resourceTypes = [];
        $this->resources = [];

        foreach ($resourceTypes as $type => $class) {
            $this->resourceTypes[$type] = $class;
        }

        $locale = $this->getCurrentLocale();
        $this->map[$locale] = $this->loadYamlMap($mapPath);
    }

    /**
     * {@inheritdoc}
     */
    public function getMap()
    {
        $locale = $this->getCurrentLocale();

        if (isset($this->map[$locale])) {
            return $this->map[$locale];
        } else {
            return $this->map[$locale] = $this->loadYamlMap($this->mapPath);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResource($key)
    {
        return $this->getResourceFromMap($key);
    }

    /**
     * {@inheritdoc}
     */
    public function hasResource($key)
    {
        $resource = $this->getResourceFromMap($key);
        return !empty($resource);
    }

    /**
     * {@inheritdoc}
     */
    protected function createResource(array $configuration, $path)
    {
        $locale = $this->getCurrentLocale();

        if ($this->isTranslatable($configuration)) {
            $path = sprintf("%s.%s", $path, $locale);
        }

        return parent::createResource($configuration, $path);
    }

    /**
     * @param $configuration
     * @param $path
     * @throws ConfigurationException
     */
    protected function validateConfiguration(array $configuration, $path)
    {
        if (strlen($path) > 255) {
            throw new ConfigurationException(
                sprintf('"%s..." key is too long. Maximum key length is 255 characters', substr($path, 0, 32))
            );
        }

        if (!array_key_exists('type', $configuration)) {
            throw new ConfigurationException(
                sprintf('Missing "type" declaration in "%s" element configuration', $path)
            );
        }
    }

    /**
     * @param $configuration
     * @throws ConfigurationException
     */
    protected function validateResourceConfiguration(array $configuration)
    {
        $validKeys = [
            'form_options',
            'constraints',
            'translatable'
        ];

        foreach ($configuration as $key => $options) {
            if ($key === 'type') {
                continue;
            }

            if ($key === 'translatable' && !is_bool($options)) {
                throw new ConfigurationException('Invalid value of "translatable" option. This option accepts only boolean value.');
            }

            if (!in_array($key, $validKeys)) {
                throw new ConfigurationException(
                    sprintf('"%s" is not a valid resource type option. Try one from: %s', $key, implode(', ', $validKeys))
                );
            }
        }
    }

    private function isTranslatable(array $configuration): bool
    {
        return (isset($configuration['translatable']) && $configuration['translatable'] === true);
    }

    private function getCurrentLocale(): string
    {
        return $this->translatableListener->getLocale() ?? $this->translatableListener->getDefaultLocale();
    }

    private function getResourceFromMap(string $key)
    {
        $map = $this->getMap();

        $parts = explode('.', $key);
        $propertyPath = '';

        foreach ($parts as $part) {
            $propertyPath .= sprintf("[%s]", $part);
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        return $accessor->getValue($map, $propertyPath);
    }

    private function loadYamlMap(string $mapPath): array
    {
        return $this->recursiveParseRawMap(Yaml::parse(file_get_contents($mapPath)));
    }
}
