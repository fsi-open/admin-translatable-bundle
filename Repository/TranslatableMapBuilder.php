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
    protected $translatableResources;

    /**
     * @param string $mapPath
     * @param string[] $resourceTypes
     * @param \FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager $localeManager
     */
    public function __construct($mapPath, $resourceTypes = array(), LocaleManager $localeManager)
    {
        $this->localeManager = $localeManager;
        $this->translatableResources = array();
        parent::__construct($mapPath, $resourceTypes);
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

            if(isset($configuration['translatable']) && $configuration['translatable'] === true) {

                if (!in_array($path, $this->translatableResources))
                    array_push($this->translatableResources, $path);

                $path = $this->getTranslatablePath($path);
            }

            $this->validateResourceConfiguration($configuration);

            $resource = $this->createResource($configuration, $path);
            $this->addConstraints($resource, $configuration);
            $this->setFormOptions($resource, $configuration);

            $map[$key] = $resource;
            $this->resources[$path] = $map[$key];
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
     * @param string $path
     * @return string
     */
    private function getTranslatablePath($path)
    {
        return sprintf('%s.%s', $path, $this->localeManager->getLocale());
    }

    /**
     * @return array
     */
    private function getTranslatableResources()
    {
        return $this->translatableResources;
    }

    /**
     * Get resource definition by key.
     * It can return resource definition object or array if key represents resources group
     *
     * @param $key
     * @return mixed
     */
    public function getResource($key)
    {
        return $this->resources[$this->getValidKey($key)];
    }

    /**
     * @param string $key
     * @return string
     */
    public function getValidKey($key)
    {
        return !in_array($key, $this->getTranslatableResources())
            ? $key
            : sprintf('%s.%s', $key, $this->localeManager->getLocale());
    }
}
