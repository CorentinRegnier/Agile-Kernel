Agile Kernel Bundle
=================

Documentation
-------------

The source of the documentation is stored in the `Resources/doc/` folder :

[Read the Documentation for master](Resources/doc/index.md)

Installation
------------

Step 1: Download AgileKernelBundle using composer

Require the bundle with composer:
```bash
    $ composer require cregnier/kernel-bundle "1.0.*"
```
Composer will install the bundle to your project's ``vendor/cregnier/kernel-bundle`` directory.

Step 2: Enable the bundle

Enable the bundle in the kernel:

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new AgileKernelBundle\AgileKernelBundle(),
            // ...
        );
    }
