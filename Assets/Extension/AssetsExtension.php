<?php

namespace AgileKernelBundle\Assets\Extension;

/**
 * Class AssetsExtension
 */
abstract class AssetsExtension implements AssetsExtensionInterface
{
    /**
     * @return array
     */
    public function getJavascriptIncludes()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getCSSIncludes()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getJavascriptCode()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getJavascriptInlineViews()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getJavascriptViews()
    {
        return [];
    }
}
