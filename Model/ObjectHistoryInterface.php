<?php

namespace AgileKernelBundle\Model;

/**
 * Class ObjectHistory
 *
 * @package AgileKernelBundle\Model
 */
interface ObjectHistoryInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return ObjectReferenceInterface
     */
    public function getObject();

    /**
     * @param ObjectReferenceInterface $object
     *
     * @return $this
     */
    public function setObject($object);

    /**
     * @return ObjectReferenceInterface
     */
    public function getActor();

    /**
     * @param ObjectReferenceInterface $actor
     *
     * @return $this
     */
    public function setActor($actor);

    /**
     * @return string
     */
    public function getAction();

    /**
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action);

    /**
     * @return string
     */
    public function getField();

    /**
     * @param string $field
     *
     * @return $this
     */
    public function setField($field);
}
