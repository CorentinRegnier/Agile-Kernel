<?php

namespace AgileKernelBundle\EventListener;

use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * Class MappingListener
 *
 * @package AgileKernelBundle\Doctrine
 */
class MappingListener extends AbstractMappingListener
{
    protected $objectReferenceClass;
    protected $objectReferenceTable;
    protected $objectHistoryClass;
    protected $objectHistoryTable;

    /**
     * MappingListener constructor.
     *
     * @param string $objectReferenceClass
     * @param string $objectReferenceTable
     * @param string $objectHistoryClass
     * @param string $objectHistoryTable
     */
    public function __construct(
        $objectReferenceClass,
        $objectReferenceTable,
        $objectHistoryClass,
        $objectHistoryTable
    ) {
        $this->objectReferenceClass = $objectReferenceClass;
        $this->objectReferenceTable = $objectReferenceTable;
        $this->objectHistoryClass   = $objectHistoryClass;
        $this->objectHistoryTable   = $objectHistoryTable;
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

        if ($className === $this->objectHistoryClass) {
            $this->setConcrete($metadata, $this->objectHistoryTable);

            $metadata->mapOneToOne([
                'targetEntity' => $this->objectReferenceClass,
                'fieldName'    => 'object',
            ]);

            $metadata->mapOneToOne([
                'targetEntity' => $this->objectReferenceClass,
                'fieldName'    => 'actor',
            ]);
        }
    }
}
