<?php

namespace AgileKernelBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use AgileKernelBundle\Util\Javascript;
use Symfony\Component\Form\FormView;
use Symfony\Component\Asset\Packages;
use AgileKernelBundle\Assets\AssetsStack;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Select2AjaxType
 *
 * @package AgileKernelBundle\Form\Type
 */
class Select2AjaxType extends Select2EntityType
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Select2AjaxType constructor.
     *
     * @param AssetsStack         $assetsStack
     * @param Packages            $assetsPackage
     * @param TranslatorInterface $translator
     * @param RequestStack        $requestStack
     * @param EntityManager       $entityManager
     * @param RouterInterface     $router
     */
    public function __construct(
        AssetsStack $assetsStack,
        Packages $assetsPackage,
        TranslatorInterface $translator,
        RequestStack $requestStack,
        EntityManager $entityManager,
        RouterInterface $router
    ) {
        parent::__construct($assetsStack, $assetsPackage, $translator, $requestStack);
        $this->router        = $router;
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (!empty($options['remote_path'])) {
            $remotePath = $options['remote_path'];
        } else {
            $remotePath = $this->router->generate($options['remote_route'], array_merge($options['remote_params'], ['page_limit' => $options['page_limit']]));
        }

        $jsOptions = [
            'multiple'                => $options['multiple'],
            'allowClear'              => $options['allow_clear'],
            'minimumInputLength'      => $options['minimum_input_length'],
            'maximumInputLength'      => $options['maximum_input_length'],
            'maximumSelectionLength'  => $options['maximum_selection_length'],
            'minimumResultsForSearch' => $options['minimum_results_for_search'],
            'language'                => $options['language'],
            'containerCssClass'       => $options['container_css_class'],
            'dropdownCssClass'        => $options['dropdown_css_class'],
            'ajax'                    => [
                'url'            => $remotePath,
                'delay'          => $options['delay'],
                'cache'          => $options['cache'],
                'data'           => 'function (params) { return { q: params.term }; }',
                'processResults' => 'function (data) { return { results: data }; }',
            ],
        ];

        $view->vars['js_options'] = Javascript::encodeJS($jsOptions, true);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'primary_key'   => 'id',
            'remote_path'   => null,
            'remote_route'  => null,
            'remote_params' => [],
            'page_limit'    => 10,
            'delay'         => 250,
            'cache'         => false,
        ]);
    }
}
