<?php

namespace AgileKernelBundle\Assets;

use AgileKernelBundle\Assets\Extension\AssetsExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AssetsStack
 */
class AssetsStack
{
    use ContainerAwareTrait;

    private $javascriptIncludes = [];

    private $javascriptCode = [];

    private $cssIncludes = [];

    private $jsInlineViews = [];

    private $jsViews = [];

    /**
     * @var AssetsExtensionInterface[]
     */
    private $extensions = [];

    /**
     * AssetsStack constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * @param mixed $extensions
     */
    public function addExtension($extensions)
    {
        $this->extensions[] = $extensions;
    }

    /**
     * @param string      $src
     * @param null|string $key
     */
    public function appendJavascriptInclude($src, $key = null)
    {
        if (null === $key) {
            $key = md5($src);
        }

        if (!isset($this->javascriptIncludes[$key])) {
            $this->javascriptIncludes[$key] = $src;
        }
    }

    /**
     * @param string      $src
     * @param null|string $key
     */
    public function appendCSSInclude($src, $key = null)
    {
        if (null === $key) {
            $key = md5($src);
        }

        if (!isset($this->cssIncludes[$key])) {
            $this->cssIncludes[$key] = $src;
        }
    }

    /**
     * @param string $code
     */
    public function appendJavascriptCode($code)
    {
        $key = md5($code);

        if (!isset($this->javascriptCode[$key])) {
            $this->javascriptCode[$key] = $code;
        }
    }

    /**
     * @return array
     */
    public function getCSSIncludes()
    {
        foreach ($this->extensions as $extension) {
            foreach ($extension->getCSSIncludes() as $src) {
                $this->appendCSSInclude($src);
            }
        }

        return $this->cssIncludes;
    }

    /**
     * @return array
     */
    public function getJavascriptIncludes()
    {
        foreach ($this->extensions as $extension) {
            foreach ($extension->getJavascriptIncludes() as $src) {
                $this->appendJavascriptInclude($src);
            }
        }

        return $this->javascriptIncludes;
    }

    /**
     * @return array
     */
    public function getJavascriptInlineViews()
    {
        $views = $this->jsInlineViews;
        foreach ($this->extensions as $extension) {
            foreach ($extension->getJavascriptInlineViews() as $view) {
                $views[] = $view;
            }
        }

        return $views;
    }

    /**
     * @return array
     */
    public function getJavascriptViews()
    {
        $views = $this->jsViews;
        foreach ($this->extensions as $extension) {
            foreach ($extension->getJavascriptViews() as $view) {
                $views[] = $view;
            }
        }

        return $views;
    }

    /**
     * @return string
     */
    public function getJavascriptCode()
    {
        foreach ($this->extensions as $extension) {
            foreach ($extension->getJavascriptCode() as $code) {
                $this->appendJavascriptCode($code);
            }
        }

        if (!empty($this->javascriptCode)) {
            return implode("\n", $this->javascriptCode);
        }

        return null;
    }
}
