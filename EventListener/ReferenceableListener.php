<?php

namespace AgileKernelBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use AgileKernelBundle\Model\ObjectReferenceInterface;
use AgileKernelBundle\Model\ReferenceableInterface;

/**
 * Class ReferenceableListener
 *
 * @package AgileKernelBundle\EventListener
 */
class ReferenceableListener implements EventSubscriber
{
    /**
     * @var string
     */
    protected $objectReferenceClass;

    /**
     * ReferenceableListener constructor.
     *
     * @param string $objectReferenceClass
     */
    public function __construct($objectReferenceClass)
    {
        $this->objectReferenceClass = $objectReferenceClass;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist => 'postPersist',
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity        = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof ReferenceableInterface) {
            if ($entity->getObjectReference() === null) {
                /** @var ObjectReferenceInterface $objectReference */
                $objectReference = new $this->objectReferenceClass();
                $objectReference
                    ->setObjectClass(get_class($entity))
                    ->setObjectId($entity->getId());

                $entityManager->persist($objectReference);

                $entity->setObjectReference($objectReference);

                $entityManager->persist($entity);
                $entityManager->flush();
            }
        }
    }
}
