<?php

namespace FSi\Bundle\AdminTranslatableBundle\Repository;

use Symfony\Component\PropertyAccess\PropertyAccess;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Repository as BaseRepository;

class Repository extends BaseRepository
{
    /**
     * Get resource by key
     *
     * @param string $key
     * @return null|mixed
     */
    public function get($key)
    {
        $entity = $this->er->get($this->builder->getValidKey($key));

        if (!isset($entity)) {
            return null;
        }

        $resource = $this->builder->getResource($key);
        $accessor = PropertyAccess::createPropertyAccessor();
        $value = $accessor->getValue($entity, $resource->getResourceProperty());

        if (isset($value) && !(is_string($value) && empty($value))) {
            return $value;
        }

        return null;
    }
}
