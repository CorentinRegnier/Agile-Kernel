<?php

namespace AgileKernelBundle\Model;

/**
 * Interface ReferenceableInterface
 *
 * @package AgileKernelBundle\Model
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
