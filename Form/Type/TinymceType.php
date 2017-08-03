<?php

namespace AgileKernelBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Asset\Packages;
use AgileKernelBundle\Assets\AssetsStack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\OptionsResolver\Options;
use AgileKernelBundle\Form\TinyMce\ExtensionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class TinymceType
 *
 * @package AgileKernelBundle\Form\Type
 */
class TinymceType extends AbstractType
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
     * @var ExtensionManager
     */
    private $extensionManager;

    static private $assetsIncluded = false;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var array
     */
    private $configuration;

    /**
     * @var array
     */
    private $contentCss;

    /**
     * TinymceType constructor.
     *
     * @param AssetsStack      $assetsStack
     * @param Packages         $assetsPackage
     * @param RouterInterface  $router
     * @param ExtensionManager $extensionManager
     * @param RequestStack     $requestStack
     * @param array            $contentCss
     * @param array            $configuration
     */
    public function __construct(
        AssetsStack $assetsStack,
        Packages $assetsPackage,
        RouterInterface $router,
        ExtensionManager $extensionManager,
        RequestStack $requestStack,
        array $contentCss,
        array $configuration
    ) {
        $this->assetsStack      = $assetsStack;
        $this->assetsPackage    = $assetsPackage;
        $this->router           = $router;
        $this->extensionManager = $extensionManager;
        $this->contentCss       = $contentCss;
        $this->configuration    = $configuration;

        $locale = $requestStack->getCurrentRequest()->getLocale();
        if ('en' !== $locale) {
            $this->lang = $locale.'_'.strtoupper($locale);
        } else {
            $this->lang = null;
        }

        if (false === self::$assetsIncluded) {
            self::$assetsIncluded = true;
            $this->assetsStack->appendJavascriptInclude($this->assetsPackage->getUrl('bundles/agilekernel/vendor/tinymce/tinymce.min.js'));
        }
    }

    /**
     * Add the file_path option
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $contentCss = [];
        foreach ($this->contentCss as $css) {
            $contentCss[] = $this->assetsPackage->getUrl($css);
        }

        $resolver->setDefaults([
            'required'                  => false,
            'height'                    => 400,
            'lang'                      => $this->lang,
            'menubar'                   => false,
            'statusbar'                 => false,
            'default_plugins'           => [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'template paste textcolor colorpicker textpattern imagetools',
            ],
            'plugins'                   => function (Options $options) {
                return array_merge(
                    $options['default_plugins'],
                    [implode(' ', $this->extensionManager->getPluginNames())]
                );
            },
            'relative_urls'             => false,
            'style_formats'             => null,
            'content_css'               => $contentCss,
            'toolbar'                   => function (Options $options) {
                $default = 'undo redo | styleselect fontsizeselect | forecolor backcolor | bold italic | alignleft 
                aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | fullscreen code';
                $plugins = $this->extensionManager->getToolbars();
                foreach ($plugins as $plugin) {
                    $default .= ' '.$plugin;
                }

                return $default;
            },
            'textcolor_map'             => null,
            'forced_root_block'         => false,
            'extended_valid_elements'   => 'i[class=myclass]',
            'paste_word_valid_elements' => 'b,strong,i,em',
            'paste_as_text'             => true,
            'external_plugins'          => function (Options $options) {
                return $this->extensionManager->getPlugins();
            },
            'toolbar1'                  => null,
            'toolbar2'                  => null,
            'fontsize_formats'          => null,
            'preview_styles'            => 'font-size',
            'body_id'                   => null,
            'body_class'                => null,
            'resize'                    => 'y',
            'agile_upload'              => null,
            'extra'                     => null,
        ]);
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $jsOptions = array_merge($this->extensionManager->getConfigurations(), [
            'selector'                => '#'.$view->vars['id'],
            'language'                => $options['lang'],
            'content_css'             => $options['content_css'],
            'style_formats'           => $options['style_formats'],
            'relative_urls'           => $options['relative_urls'],
            'plugins'                 => $options['plugins'],
            'toolbar'                 => $options['toolbar'],
            'menubar'                 => $options['menubar'],
            'statusbar'               => $options['statusbar'],
            'height'                  => $options['height'],
            'textcolor_map'           => $options['textcolor_map'],
            'external_plugins'        => $options['external_plugins'],
            'forced_root_block'       => $options['forced_root_block'],
            'extended_valid_elements' => $options['extended_valid_elements'],
            'toolbar1'                => $options['toolbar1'],
            'toolbar2'                => $options['toolbar2'],
            'fontsize_formats'        => $options['fontsize_formats'],
            'resize'                  => $options['resize'],
            'preview_styles'          => $options['preview_styles'],
            'body_id'                 => $options['body_id'],
            'body_class'              => $options['body_class'],
            'agile_upload'            => $options['agile_upload'],
        ]);

        $jsOptions = array_merge($jsOptions, $this->configuration);

        if (!empty($options['extra'])) {
            $jsOptions = array_merge($jsOptions, $options['extra']);
        }

        $view->vars['js_options'] = json_encode($jsOptions);
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return TextareaType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'agile_tinymce';
    }
}
