<?php

namespace FSi\Bundle\AdminTranslatableBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Repository as BaseRepository;

class Repository extends BaseRepository
{
    /**
     * @param \FSi\Bundle\AdminTranslatableBundle\Repository\TranslatableMapBuilder $builder
     * @param \Doctrine\Common\Persistence\ObjectRepository $er
     */
    public function __construct(TranslatableMapBuilder $builder, ObjectRepository $er)
    {
        parent::__construct($builder, $er);
    }

    /**
     * Get resource by key
     *
     * @param string $key
     * @return null|mixed
     */
    public function get($key)
    {
        $entity = $this->er->get($this->builder->getRealKey($key));

        if (!isset($entity)) {
            return null;
        }

        $resource = $this->builder->getResource($this->builder->getRealKey($key));
        $accessor = PropertyAccess::createPropertyAccessor();
        $value = $accessor->getValue($entity, $resource->getResourceProperty());

        if (isset($value) && !(is_string($value) && empty($value))) {
            return $value;
        }

        return null;
    }
}
