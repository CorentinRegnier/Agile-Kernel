<?php

namespace AgileKernelBundle\Model;

/**
 * Class ObjectReference
 *
 * @package AgileKernelBundle\Model
 */
interface ObjectReferenceInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getObjectClass();

    /**
     * @param string $objectClass
     *
     * @return $this
     */
    public function setObjectClass($objectClass);

    /**
     * @return int
     */
    public function getObjectId();

    /**
     * @param int $objectId
     *
     * @return $this
     */
    public function setObjectId($objectId);

    /**
     * @return object
     */
    public function getObject();

    /**
     * @param object $object
     *
     * @return $this
     */
    public function setObject($object);
}
