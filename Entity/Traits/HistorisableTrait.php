<?php

namespace AgileKernelBundle\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use AgileKernelBundle\Model\ObjectHistoryInterface;

trait HistorisableTrait
{
    /**
     * @var ArrayCollection|ObjectHistoryInterface[]
     */
    protected $objectHistories;

    /**
     * @return ArrayCollection|ObjectHistoryInterface[]
     */
    public function getObjectHistories()
    {
        return $this->objectHistories;
    }

    /**
     * @param ArrayCollection|ObjectHistoryInterface[] $objectHistories
     *
     * @return $this
     */
    public function setObjectHistories($objectHistories)
    {
        $this->objectHistories = $objectHistories;

        return $this;
    }
}
