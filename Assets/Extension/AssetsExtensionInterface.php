<?php
namespace AgileKernelBundle\Assets\Extension;

/**
 * Class AssetsExtension
 *
 * @package AgileKernelBundle\Assets\Extension
 */
interface AssetsExtensionInterface
{
    /**
     * @return array
     */
    public function getJavascriptIncludes();

    /**
     * @return array
     */
    public function getCSSIncludes();

    /**
     * @return array
     */
    public function getJavascriptCode();

    /**
     * @return array
     */
    public function getJavascriptInlineViews();

    /**
     * @return array
     */
    public function getJavascriptViews();
}
