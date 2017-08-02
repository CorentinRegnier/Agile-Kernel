<?php

namespace AgileKernelBundle\Form\TinyMce;

/**
 * Interface TinyMceExtensionInterface
 */
interface TinyMceExtensionInterface
{
    /**
     * 'agile_template' => 'path/to/plugin.min.js'
     *
     * @return array
     */
    function getPlugins();

    /**
     * @return array
     */
    function getConfigurations();

    /**
     * @return array
     */
    function getButtons();

    /**
     * @return array
     */
    function getToolbars();

    /**
     * Extension name
     *
     * @return string
     */
    function getName();
}
