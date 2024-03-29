<?php

namespace AgileKernelBundle\Form\TinyMce;

/**
 * Class AbstractTinyMceExtension
 *
 * @package AgileKernelBundle\Form\TinyMce
 */
abstract class AbstractTinyMceExtension implements TinyMceExtensionInterface
{
    /**
     * @return array
     */
    function getPlugins()
    {
        return [];
    }

    /**
     * @return array
     */
    function getConfigurations()
    {
        return [];
    }

    /**
     * @return array
     */
    function getButtons()
    {
        return [$this->getName()];
    }

    /**
     * @return array
     */
    function getToolbars()
    {
        return [$this->getName()];
    }
}
