<?php

namespace AgileKernelBundle\Manager;

use Doctrine\ORM\EntityManager;

/**
 * Class AbstractBaseManager
 */
class AbstractBaseManager
{
    protected $em;
    protected $repository;
    protected $class;

    /**
     * @param EntityManager $entityManager
     * @param string        $class
     */
    public function __construct(EntityManager $entityManager, $class)
    {
        $this->em = $entityManager;
        $this->setRepository($class);
        $this->class = $entityManager->getClassMetadata($class)->getName();
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
        $this->repository = $this->em->getRepository($entityClass);
    }

    /**
     * @param $entity
     */
    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @param $entity
     */
    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}
