<?php

namespace AgileKernelBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class Select2EntityType
 *
 * @package AgileKernelBundle\Form\Type
 */
class Select2EntityType extends Select2Type
{
    /**
     * @return mixed
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
