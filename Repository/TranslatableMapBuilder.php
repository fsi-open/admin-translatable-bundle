<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Repository;

use FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder as BaseMapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;

use function array_key_exists;

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

    public function getMap(): array
    {
        $locale = $this->getCurrentLocale();

        if (false === array_key_exists($locale, $this->map)) {
            $this->map[$locale] = $this->loadYamlMap($this->mapPath);
        }

        return $this->map[$locale];
    }

    public function getResource(string $key)
    {
        return $this->getResourceFromMap($key);
    }

    public function hasResource($key): bool
    {
        $resource = $this->getResourceFromMap($key);
        return !empty($resource);
    }

    protected function createResource(array $configuration, string $path): ResourceInterface
    {
        $locale = $this->getCurrentLocale();

        if (true === $this->isTranslatable($configuration)) {
            $path = sprintf("%s.%s", $path, $locale);
        }

        return parent::createResource($configuration, $path);
    }

    /**
     * @param array $configuration
     * @param string $path
     * @return void
     * @throws ConfigurationException
     */
    protected function validateConfiguration(array $configuration, string $path): void
    {
        if (strlen($path) > 255) {
            throw new ConfigurationException(sprintf(
                '"%s..." key is too long. Maximum key length is 255 characters',
                substr($path, 0, 32)
            ));
        }

        if (false === array_key_exists('type', $configuration)) {
            throw new ConfigurationException(
                sprintf('Missing "type" declaration in "%s" element configuration', $path)
            );
        }
    }

    /**
     * @param array $configuration
     * @return void
     * @throws ConfigurationException
     */
    protected function validateResourceConfiguration(array $configuration): void
    {
        $validKeys = ['form_options', 'constraints', 'translatable'];

        foreach ($configuration as $key => $options) {
            if ($key === 'type') {
                continue;
            }

            if ($key === 'translatable' && !is_bool($options)) {
                throw new ConfigurationException(
                    'Invalid value of "translatable" option. This option accepts only boolean value.'
                );
            }

            if (false === in_array($key, $validKeys, true)) {
                throw new ConfigurationException(
                    sprintf(
                        '"%s" is not a valid resource type option. Try one from: %s',
                        $key,
                        implode(', ', $validKeys)
                    )
                );
            }
        }
    }

    private function isTranslatable(array $configuration): bool
    {
        return true === array_key_exists('translatable', $configuration) && true === $configuration['translatable'];
    }

    private function getCurrentLocale(): ?string
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
