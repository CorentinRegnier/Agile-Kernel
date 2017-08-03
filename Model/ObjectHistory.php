<?php

namespace AgileKernelBundle\Model;

use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * Class ObjectHistory
 *
 * @package AgileKernelBundle\Model
 */
class ObjectHistory implements ObjectHistoryInterface
{
    use Timestampable;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var ObjectReferenceInterface
     */
    protected $object;

    /**
     * @var ObjectReferenceInterface
     */
    protected $actor;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $field;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ObjectReferenceInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param ObjectReferenceInterface $object
     *
     * @return $this
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return ObjectReferenceInterface
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * @param ObjectReferenceInterface $actor
     *
     * @return $this
     */
    public function setActor($actor)
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $field
     *
     * @return $this
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }
}
