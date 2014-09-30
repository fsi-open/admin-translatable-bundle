<?php

namespace FSi\Bundle\AdminTranslatableBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Repository as BaseRepository;

class Repository extends BaseRepository
{
    /**
     * @inheritdoc
     */
    public function __construct(TranslatableMapBuilder $builder, ResourceValueRepository $resourceValueRepository, $resourceValueClass)
    {
        parent::__construct($builder, $resourceValueRepository, $resourceValueClass);
    }

    /**
     * Get resource by key
     *
     * @param string $key
     * @return null|mixed
     */
    public function get($key)
    {
        return parent::get($this->builder->getTranslatedKey($key));
    }

    /**
     * @param string $key
     * @param mixed
     */
    public function set($key, $value)
    {
        parent::set($this->builder->getTranslatedKey($key), $value);
    }
}
