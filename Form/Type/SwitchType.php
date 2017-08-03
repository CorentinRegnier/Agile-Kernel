<?php

namespace AgileKernelBundle\Form\Type;

use AgileKernelBundle\Util\Javascript;
use Symfony\Component\Form\FormView;
use Symfony\Component\Asset\Packages;
use AgileKernelBundle\Assets\AssetsStack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\RadioType;

/**
 * Class SwitchType
 *
 * @package AgileKernelBundle\Form\Type
 */
class SwitchType extends AbstractType
{
    /**
     * @var AssetsStack
     */
    private $assetsStack;

    /**
     * @var Packages
     */
    private $assetsPackage;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    static private $assetsIncluded = false;

    /**
     * SwitchType constructor.
     *
     * @param AssetsStack         $assetsStack
     * @param Packages            $assetsPackage
     * @param TranslatorInterface $translator
     */
    public function __construct(
        AssetsStack $assetsStack,
        Packages $assetsPackage,
        TranslatorInterface $translator
    ) {
        $this->assetsStack   = $assetsStack;
        $this->assetsPackage = $assetsPackage;
        $this->translator    = $translator;

        if (false === self::$assetsIncluded) {
            self::$assetsIncluded = true;
            $this->assetsStack->appendCSSInclude($this->assetsPackage->getUrl('bundles/agilekernel/vendor/bootstrap-switch/bootstrap-switch.min.css'));
            $this->assetsStack->appendJavascriptInclude($this->assetsPackage->getUrl('bundles/agilekernel/vendor/bootstrap-switch/bootstrap-switch.min.js'));
        }
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $jsOptions = [
            'size'        => $options['size'],
            'onText'      => $options['onText'],
            'offText'     => $options['offText'],
            'radioAllOff' => $options['radioAllOff'],
        ];

        $view->vars['js_options'] = Javascript::encodeJS($jsOptions, true);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

        $onText  = $this->translator->trans('common.form.switch.on.label', [], 'AgileKernelBundle');
        $offText = $this->translator->trans('common.form.switch.off.label', [], 'AgileKernelBundle');
        $resolver->setDefaults([
            'required'    => false,
            'size'        => 'mini',
            'onText'      => $onText,
            'offText'     => $offText,
            'radioAllOff' => true,
        ]);
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return RadioType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'agile_switch';
    }
}
