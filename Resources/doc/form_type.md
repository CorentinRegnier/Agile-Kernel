Agile Kernel Bundle
=================

Form Type Documentation
-----------------------

### Color Form Type (Supported on IE8+)

```php
# /src/AppBundle/Form/Type/CarType.php

use AgileKernelBundle\Form\Type\ColorType;

public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('color', ColorType::class)
```

### Google Map Form Type

```php
# /src/AppBundle/Form/Type/CarType.php

use AgileKernelBundle\Form\Type\GMapType;

public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', GMapType::class)
```

### Select2 Form Type

```php
# /src/AppBundle/Form/Type/CarType.php

use AgileKernelBundle\Form\Type\Select2Type;

public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', Select2Type::class)
```

### Switch Form Type

```php
# /src/AppBundle/Form/Type/CarType.php

use AgileKernelBundle\Form\Type\SwitchType;

public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled', SwitchType::class)
```

### Tinymce Form Type

```php
# /src/AppBundle/Form/Type/CarType.php

use AgileKernelBundle\Form\Type\TinymceType;

public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TinymceType::class)
```
