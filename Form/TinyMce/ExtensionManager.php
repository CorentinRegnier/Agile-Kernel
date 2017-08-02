<?php

namespace AgileKernelBundle\Form\TinyMce;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Asset\Packages;

/**
 * Class ExtensionManager
 */
class ExtensionManager
{
    /**
     * @var Packages
     */
    private $assetsPackage;

    /**
     * @var TinyMceExtensionInterface[]
     */
    private $extensions = [];

    /**
     * @var array
     */
    private $plugins;

    function __construct(Packages $assetsPackage)
    {
        $this->assetsPackage = $assetsPackage;
    }

    public function addExtension(TinyMceExtensionInterface $extension)
    {
        $name = $extension->getName();
        if (isset($this->extensions[$name])) {
            throw new InvalidConfigurationException(sprintf('Extension "%s" has already been loaded', $name));
        }
        $this->extensions[$name] = $extension;
    }

    /**
     * @return array
     */
    public function getPlugins()
    {
        if (null === $this->plugins) {
            $this->plugins = [];

            foreach ($this->extensions as $name => $extension) {
                foreach ($extension->getPlugins() as $name => $path) {
                    $this->plugins[$name] = $this->assetsPackage->getUrl($path);
                }
            }
        }

        return $this->plugins;
    }

    public function getPluginNames()
    {
        return array_keys($this->getPlugins());
    }

    /**
     * @return array
     */
    public function getButtons()
    {
        return $this->flatten('getButtons');
    }

    /**
     * @return array
     */
    public function getToolbars()
    {
        return $this->flatten('getToolbars');
    }

    /**
     * @return array
     */
    public function getConfigurations()
    {
        $configs = [];
        foreach ($this->extensions as $name => $extension) {
            $configs = array_merge($configs, $extension->getConfigurations());
        }

        return $configs;
    }

    private function flatten($method)
    {
        $return = [];
        foreach ($this->extensions as $name => $extension) {
            foreach ($extension->$method() as $item) {
                $return[] = $item;
            }
        }

        return $return;
    }
}
