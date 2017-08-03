<?php

namespace AgileKernelBundle\Form\Type;

use AgileKernelBundle\Form\DataTransformer\Select2TagTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Select2TagType
 */
class Select2TagType extends Select2Type
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->resetModelTransformers()->resetViewTransformers();
        $builder->addViewTransformer(new Select2TagTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'tags' => true,
        ]);
    }
}
