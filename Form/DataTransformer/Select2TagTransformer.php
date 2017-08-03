<?php

namespace AgileKernelBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class Select2TagTransformer
 */
class Select2TagTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $array
     *
     * @return array
     */
    public function transform($array)
    {
        if (empty($array)) {
            return [];
        }

        return $array;
    }

    /**
     * @param mixed $array
     *
     * @return array
     */
    public function reverseTransform($array)
    {
        if (!is_array($array) || empty($array)) {
            return [];
        }

        return $array;
    }
}
