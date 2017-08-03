<?php

namespace AgileKernelBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * AbstractBaseManager
 */
class AbstractBaseManager
{
    protected $om;
    protected $repository;
    protected $class;

    /**
     * @param ObjectManager $objectManager
     * @param string        $class
     */
    public function __construct(ObjectManager $objectManager, $class)
    {
        $this->om = $objectManager;
        $this->setRepository($class);
        $this->class = $objectManager->getClassMetadata($class)->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return mixed
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param $entityClass
     */
    public function setRepository($entityClass)
    {
        $this->repository = $this->om->getRepository($entityClass);
    }

    /**
     * @param $entity
     */
    public function save($entity)
    {
        $this->om->persist($entity);
        $this->om->flush();
    }

    /**
     * @param $entity
     */
    public function remove($entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
    }
}
