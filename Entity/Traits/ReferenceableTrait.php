<?php

namespace AgileKernelBundle\Entity\Traits;

use AgileKernelBundle\Model\ObjectReferenceInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait ReferenceableTrait
 */
trait ReferenceableTrait
{
    /**
     * @var ObjectReferenceInterface
     * @ORM\OneToOne(targetEntity="AgileKernelBundle\Model\ObjectReference")
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
