<?php

namespace AgileKernelBundle\Entity\Traits;

use AgileKernelBundle\Model\ObjectReferenceInterface;

trait ReferenceableTrait
{
    /**
     * @var ObjectReferenceInterface
     */
    protected $objectReference;

    /**
     * @return ObjectReferenceInterface
     */
    public function getObjectReference()
    {
        return $this->objectReference;
    }

    /**
     * @param ObjectReferenceInterface $objectReference
     *
     * @return $this
     */
    public function setObjectReference(ObjectReferenceInterface $objectReference)
    {
        $this->objectReference = $objectReference;

        return $this;
    }
}
