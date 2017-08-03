<?php

namespace AgileKernelBundle\Form\Type;

use AgileKernelBundle\Util\Javascript;
use Symfony\Component\Form\FormView;
use Symfony\Component\Asset\Packages;
use AgileKernelBundle\Assets\AssetsStack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\CountryType as BaseCountryType;

/**
 * Class CountryType
 *
 * @package AgileKernelBundle\Form\Type
 */
class CountryType extends AbstractType
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

    /**
     * @var RequestStack
     */
    private $requestStack;

    static public $assetsIncluded = false;

    /**
     * CountryType constructor.
     *
     * @param AssetsStack         $assetsStack
     * @param Packages            $assetsPackage
     * @param TranslatorInterface $translator
     * @param RequestStack        $requestStack
     */
    public function __construct(
        AssetsStack $assetsStack,
        Packages $assetsPackage,
        TranslatorInterface $translator,
        RequestStack $requestStack
    ) {
        $this->assetsStack   = $assetsStack;
        $this->assetsPackage = $assetsPackage;
        $this->translator    = $translator;
        $this->requestStack  = $requestStack;
        $locale              = strtolower($this->requestStack->getCurrentRequest()->getLocale());

        if (false === self::$assetsIncluded) {
            self::$assetsIncluded = true;
            $this->assetsStack->appendCSSInclude($this->assetsPackage->getUrl('bundles/agilekernel/vendor/select2/css/select2.min.css'));
            $this->assetsStack->appendJavascriptInclude($this->assetsPackage->getUrl('bundles/agilekernel/vendor/select2/js/select2.full.min.js'));
            $this->assetsStack->appendJavascriptInclude($this->assetsPackage->getUrl('bundles/agilekernel/vendor/select2/js/i18n/'.$locale.'.js'));
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
            'language'          => $options['language'],
            'containerCssClass' => $options['container_css_class'],
            'dropdownCssClass'  => $options['dropdown_css_class'],
        ];

        $view->vars['js_options'] = Javascript::encodeJS($jsOptions, true);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr'                => function (Options $options) {
                if (empty($options['empty_message'])) {
                    $emptyMessage = 'common.form.country.placeholder';
                } else {
                    $emptyMessage = $options['empty_message'];
                }

                return ['data-placeholder' => $this->translator->trans($emptyMessage, [], $options['translation_domain'])];
            },
            'empty_message'       => null,
            'allow_clear'         => false,
            'container_css_class' => null,
            'dropdown_css_class'  => null,
            'language'            => 'fr',
        ]);
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return BaseCountryType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'agile_country';
    }
}
