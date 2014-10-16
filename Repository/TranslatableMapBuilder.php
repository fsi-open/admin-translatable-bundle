<?php

namespace FSi\Bundle\AdminTranslatableBundle\Repository;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder as BaseMapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface;

class TranslatableMapBuilder extends BaseMapBuilder
{
    /**
     * @var \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager
     */
    protected $localeManager;

    /**
     * @var array
     */
    protected $translatedKeys;

    /**
     * @param string $mapPath
     * @param string[] $resourceTypes
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     */
    public function __construct($mapPath, $resourceTypes = array(), LocaleManager $localeManager)
    {
        $this->localeManager = $localeManager;
        $this->translatedKeys = array();
        parent::__construct($mapPath, $resourceTypes);
    }

    /**
     * @param string $key
     * @return string
     */
    public function getTranslatedKey($key)
    {
        $translatedKey = $this->translateKey($key);
        if (isset($this->translatedKeys[$translatedKey])) {
            return $translatedKey;
        }
        return $key;
    }

    /**
     * @param array $rawMap
     * @param null|string $parentPath
     * @throws \FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException
     * @return array
     */
    protected function recursiveParseRawMap($rawMap = array(), $parentPath = null)
    {
        $map = array();

        if (!is_array($rawMap)) {
            return $map;
        }

        foreach ($rawMap as $key => $configuration) {
            $path = (isset($parentPath))
                ? $parentPath . '.' . $key
                : $key;

            $this->validateConfiguration($configuration, $path);

            if ($configuration['type'] == 'group') {
                unset($configuration['type']);
                $map[$key] = $this->recursiveParseRawMap($configuration, $path);
                continue;
            }

            $this->validateResourceConfiguration($configuration);

            $this->addToTranslatedKeysIfTranslatable($configuration, $path);

            if ($this->isTranslatable($configuration)) {
                foreach ($this->localeManager->getLocales() as $locale) {
                    $this->parseConfiguration($configuration, $path, $locale);
                }
            } else {
                $this->parseConfiguration($configuration, $path);
            }
        }

        return $map;
    }

    /**
     * @param $configuration
     * @param $path
     * @throws \FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException
     */
    protected function validateConfiguration($configuration, $path)
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
     * @throws \FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException
     */
    protected function validateResourceConfiguration($configuration)
    {
        $validKeys = array(
            'form_options',
            'constraints',
            'translatable'
        );

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

    /**
     * @param array $configuration
     * @param string $key
     * @param string|null $locale
     */
    private function parseConfiguration($configuration, $key, $locale = null)
    {
        if ($locale !== null) {
            $key = $this->translateKey($key, $locale);
        }
        $resource = $this->createAndConfigureResource($configuration, $key);
        $this->addResourceToMap($key, $resource, $locale);
        $this->resources[$key] = $resource;
    }

    /**
     * Add to resources map
     *
     * @param string $key
     * @param mixed $resource
     * @param string| null $locale
     */
    private function addResourceToMap($key, $resource, $locale = null)
    {
        if ($locale === null || $locale === $this->localeManager->getLocale()) {
            $this->map[$key] = $resource;
        }
    }

    /**
     * @param $configuration
     * @param $path
     * @return ResourceInterface
     */
    private function createAndConfigureResource($configuration, $path)
    {
        $resource = $this->createResource($configuration, $path);

        $this->addConstraints($resource, $configuration);
        $this->setFormOptions($resource, $configuration);

        return $resource;
    }

    /**
     * @param $key
     * @param null|string $locale
     * @return string
     */
    private function translateKey($key, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->localeManager->getLocale();
        }
        return sprintf('%s.%s', $key, $locale);
    }

    /**
     * @param array $configuration
     * @return boolean
     */
    private function isTranslatable(array $configuration)
    {
        return (isset($configuration['translatable']) && $configuration['translatable'] === true);
    }

    /**
     * @param array $configuration
     * @param string $key
     */
    private function addToTranslatedKeysIfTranslatable(array $configuration, $key)
    {
        if (!$this->isTranslatable($configuration)) {
            return;
        }

        foreach ($this->localeManager->getLocales() as $locale) {
            $translatedKey = $this->translateKey($key, $locale);
            $this->translatedKeys[$translatedKey] = $translatedKey;
        }
    }
}
