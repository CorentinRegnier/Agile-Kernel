<?php

namespace AgileKernelBundle\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

abstract class AbstractMappingListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    abstract public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs);

    protected function setConcrete(ClassMetadataInfo $metadata, $tableName)
    {
        $metadata->setPrimaryTable([
            'name' => $tableName,
        ]);
        $metadata->isMappedSuperclass = false;
    }
}
