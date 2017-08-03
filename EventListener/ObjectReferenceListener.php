<?php

namespace AgileKernelBundle\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AgileKernelBundle\Model\ObjectReferenceInterface;

class ObjectReferenceListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::postLoad => 'postLoad',
        ];
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof ObjectReferenceInterface) {
            $em  = $args->getEntityManager();
            $uow = $em->getUnitOfWork();

            $class = new \ReflectionClass($object);

            $property = $class->getProperty('objectClass');
            $property->setAccessible(true);
            $objectClass = $property->getValue($object);

            $property = $class->getProperty('objectId');
            $property->setAccessible(true);
            $objectId = $property->getValue($object);

            $objectMetadata = $em->getClassMetadata($objectClass);

            $identityMap   = $uow->getIdentityMap();
            $relatedIdHash = $objectId;
            if (isset($identityMap[$objectMetadata->rootEntityName][$relatedIdHash])) {
                $value = $identityMap[$objectMetadata->rootEntityName][$relatedIdHash];
            } else {
                $value = $em->getProxyFactory()->getProxy($objectClass, ['id' => $objectId]);
                $uow->registerManaged($value, ['id' => $objectId], []);
            }

            $property = $class->getProperty('object');
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }
    }
}
