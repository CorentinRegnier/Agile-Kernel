<?php

namespace AgileKernelBundle\Model;

/**
 * Interface ReferenceableInterface
 */
interface ReferenceableInterface
{
    /**
     * @param ObjectReferenceInterface $objectReference
     *
     * @return $this
     */
    public function setObjectReference(ObjectReferenceInterface $objectReference);

    /**
     * @return ObjectReferenceInterface
     */
    public function getObjectReference();
}
