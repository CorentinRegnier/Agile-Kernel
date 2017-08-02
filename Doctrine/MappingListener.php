<?php

namespace AgileKernelBundle\Doctrine;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class MappingListener
 */
class MappingListener extends AbstractMappingListener
{
    protected $objectReferenceClass;
    protected $objectReferenceTable;

    /**
     * MappingListener constructor.
     *
     * @param string $objectReferenceClass
     * @param string $objectReferenceTable
     */
    public function __construct($objectReferenceClass, $objectReferenceTable)
    {
        $this->objectReferenceClass = $objectReferenceClass;
        $this->objectReferenceTable = $objectReferenceTable;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadataInfo $metadata */
        $metadata  = $eventArgs->getClassMetadata();
        $className = $metadata->getName();
        if ($className === $this->objectReferenceClass) {
            $this->setConcrete($metadata, $this->objectReferenceTable);
        }
    }
}
