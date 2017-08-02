<?php

namespace AgileKernelBundle\Entity\Traits;

use AgileKernelBundle\Model\StatusInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class StatusTrait
 */
trait StatusTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", name="status", length=50, nullable=false)
     */
    protected $status = StatusInterface::STATUS_ENABLED;

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public static function getAvailableStatusList()
    {
        return [
            StatusInterface::STATUS_ENABLED,
            StatusInterface::STATUS_DISABLED,
            StatusInterface::STATUS_DELETED,
        ];
    }

    /**
     * @return array
     */
    public static function getDisplayedStatusList()
    {
        return [
            'common.status.'.StatusInterface::STATUS_ENABLED  => StatusInterface::STATUS_ENABLED,
            'common.status.'.StatusInterface::STATUS_DISABLED => StatusInterface::STATUS_DISABLED,
        ];
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        if (!in_array($status, $this->getAvailableStatusList())) {
            throw new \InvalidArgumentException('Status not allowed');
        }
        $this->status = $status;

        return $this;
    }
}
