<?php

namespace AgileKernelBundle\Exception;

/**
 * Class UnexpectedTypeException
 */
class UnexpectedTypeException extends \RuntimeException
{
    /**
     * @param mixed $value [optional]
     * @param array $expectedTypes
     */
    public function __construct($value = '', $expectedTypes)
    {
        if (!is_array($expectedTypes)) {
            $expectedTypes = [$expectedTypes];
        }
        parent::__construct(sprintf('Expected argument of type "%s", "%s" given', implode('", "', $expectedTypes), is_object($value) ? get_class($value) : gettype($value)));
    }
}
