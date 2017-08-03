<?php

namespace AgileKernelBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AgileKernelBundle\Model\ObjectHistoryInterface;

trait HistorisableEntityTrait
{
    /**
     * @var ArrayCollection|ObjectHistoryInterface[]
     * @ORM\OneToMany(targetEntity="AgileKernelBundle\Model\ObjectHistory")
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
