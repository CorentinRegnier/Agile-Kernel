<?php

namespace AgileKernelBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Asset\Packages;
use AgileKernelBundle\Assets\AssetsStack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class ColorType
 *
 * @package AgileKernelBundle\Form\Type
 */
class ColorType extends AbstractType
{
    /**
     * @var AssetsStack
     */
    private $assetsStack;

    /**
     * @var Packages
     */
    private $assetsPackage;

    static private $assetsIncluded = false;

    /**
     * ColorType constructor.
     *
     * @param AssetsStack $assetsStack
     * @param Packages    $assetsPackage
     */
    public function __construct(
        AssetsStack $assetsStack,
        Packages $assetsPackage
    ) {
        $this->assetsStack   = $assetsStack;
        $this->assetsPackage = $assetsPackage;

        if (false === self::$assetsIncluded) {
            self::$assetsIncluded = true;
            $this->assetsStack->appendCSSInclude($this->assetsPackage->getUrl('bundles/agilekernel/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css'));
            $this->assetsStack->appendJavascriptInclude($this->assetsPackage->getUrl('bundles/agilekernel/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js'));
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required'    => false,
            'format'      => false,
            'horizontal'  => false,
            'inline'      => false,
            'align'       => 'right',
            'customClass' => null,
        ]);
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $jsOptions = [
            'format'      => $options['format'],
            'horizontal'  => $options['horizontal'],
            'inline'      => $options['inline'],
            'align'       => $options['align'],
            'customClass' => $options['customClass'],
        ];

        $view->vars['js_options'] = json_encode($jsOptions);
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'agile_color';
    }
}
