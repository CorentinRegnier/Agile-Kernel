<?php

namespace AgileKernelBundle\Form\Extension;

use Symfony\Component\Form\FormView;
use Symfony\Component\Asset\Packages;
use AgileKernelBundle\Assets\AssetsStack;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DateTypeExtension extends AbstractTypeExtension
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

    function __construct(AssetsStack $assetsStack, Packages $assetsPackage, RequestStack $requestStack)
    {
        $this->assetsStack  = $assetsStack;
        $this->assetsPackage = $assetsPackage;

        if (!self::$assetsIncluded) {
            self::$assetsIncluded = true;
            $this->assetsStack->appendCSSInclude($this->assetsPackage->getUrl('bundles/agilekernel/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css'));
            $this->assetsStack->appendJavascriptInclude($this->assetsPackage->getUrl('bundles/agilekernel/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js'));
            $locale = $requestStack->getCurrentRequest()->getLocale();
            $this->assetsStack->appendJavascriptInclude($this->assetsPackage->getUrl('bundles/agilekernel/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.' . $locale . '.min.js'));
        }
    }

    public function getExtendedType()
    {
        return DateType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr'   => [
                'class' => 'date-picker'
            ],
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['format'] = strtolower($options['format']);
    }
}
