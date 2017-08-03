<?php

namespace AgileKernelBundle\Model;

use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * Class ObjectReference
 *
 * @package AgileKernelBundle\Model
 */
class ObjectReference implements ObjectReferenceInterface
{
    use Timestampable;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $objectClass;

    /**
     * @var int
     */
    protected $objectId;

    /**
     * @var object
     */
    protected $object;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * @param string $objectClass
     *
     * @return $this
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;

        return $this;
    }

    /**
     * @return int
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param int $objectId
     *
     * @return $this
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param object $object
     *
     * @return $this
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }
}
