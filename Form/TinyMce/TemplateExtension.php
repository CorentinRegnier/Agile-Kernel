<?php

namespace AgileKernelBundle\Form\TinyMce;

use Symfony\Component\Templating\EngineInterface;

class TemplateExtension extends AbstractTinyMceExtension
{
    /**
     * @var array
     */
    private $templatesViews;

    private $templates;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * TemplateExtension constructor.
     *
     * @param EngineInterface $templating
     * @param array           $templatesViews
     */
    public function __construct(EngineInterface $templating, array $templatesViews)
    {
        $this->templating     = $templating;
        $this->templatesViews = $templatesViews;
    }

    /**
     * @return array
     */
    public function getPlugins()
    {
        return [
            'agile_templates' => 'bundles/agilekernel/tinymce/plugins/agile_templates/plugin.min.js',
        ];
    }

    /**
     * @param $view
     *
     * @return string
     */
    protected function render($view)
    {
        return $this->templating->render($view);
    }

    /**
     * @return array
     */
    public function getConfigurations()
    {
        if (null === $this->templates) {
            $this->templates = [];
            foreach ($this->templatesViews as $key => $template) {
                $this->templates[] = [
                    'title'   => $template['title'],
                    'content' => $this->render($template['content']),
                ];
            }
        }

        return [
            'agile_templates' => $this->templates,
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'agile_templates';
    }
}
