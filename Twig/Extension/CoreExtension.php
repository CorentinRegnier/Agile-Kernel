<?php

namespace AgileKernelBundle\Twig\Extension;

use AgileKernelBundle\Assets\AssetsStack;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class CoreExtension
 */
class CoreExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    private $container;

    /**
     * @var AssetsStack
     */
    private $assetsStack;

    /**
     * @var array
     */
    private $agileGlobals;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * CoreExtension constructor.
     *
     * @param ContainerInterface    $container
     * @param UrlGeneratorInterface $generator
     * @param AssetsStack           $assetsStack
     * @param array                 $agileGlobals
     */
    public function __construct(
        ContainerInterface $container,
        UrlGeneratorInterface $generator,
        AssetsStack $assetsStack,
        array $agileGlobals
    ) {
        $this->container    = $container;
        $this->generator    = $generator;
        $this->assetsStack  = $assetsStack;
        $this->agileGlobals = $agileGlobals;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setGlobal($key, $value)
    {
        $this->agileGlobals[$key] = $value;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('ucfirst', [$this, 'ucfirst']),
            new \Twig_SimpleFilter('floor', [$this, 'floor']),
            new \Twig_SimpleFilter('ceil', [$this, 'ceil']),
            new \Twig_SimpleFilter('json_encode', [$this, 'jsonEncode']),
            new \Twig_SimpleFilter('is_numeric', [$this, 'isNumeric']),
            new \Twig_SimpleFilter('localizeddate', [$this, 'localizedDateFilter'], ['needs_environment' => true]),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('append_js_include', [$this->assetsStack, 'appendJavascriptInclude']),
            new \Twig_SimpleFunction('append_css_include', [$this->assetsStack, 'appendCSSInclude']),
            new \Twig_SimpleFunction('append_js_code', [$this->assetsStack, 'appendJavascriptCode']),
            new \Twig_SimpleFunction('get_js_code', [$this->assetsStack, 'getJavascriptCode'], [
                'is_safe' => [
                    'html' => true,
                    'js'   => true,
                ],
            ]),
            new \Twig_SimpleFunction('get_js_includes', [$this->assetsStack, 'getJavascriptIncludes']),
            new \Twig_SimpleFunction('get_js_inline_views', [$this->assetsStack, 'getJavascriptInlineViews']),
            new \Twig_SimpleFunction('get_css_includes', [$this->assetsStack, 'getCSSIncludes']),
            new \Twig_SimpleFunction('now', [$this, 'now']),
            new \Twig_SimpleFunction('get_available_locales', [$this, 'getAvailableLocales']),
            new \Twig_SimpleFunction('change_locale_url', [$this, 'getChangeLocaleUrl']),
            new \Twig_SimpleFunction('translate_locale', [$this, 'getLocaleTranslation']),
            new \Twig_SimpleFunction('uri_replace_query', [$this, 'getUriReplaceQuery']),
            new \Twig_SimpleFunction('route_exist', [$this, 'getRouteExist']),
        ];
    }

    /**
     * @param string      $locale
     * @param null|string $displayLocale Force the display locale
     *
     * @return null|string
     */
    public function getLocaleTranslation($locale, $displayLocale = null)
    {
        return Intl::getLocaleBundle()->getLocaleName($locale, $displayLocale ?: $locale);
    }

    /**
     * @return mixed
     */
    public function getAvailableLocales()
    {
        return $this->container->getParameter('agile_kernel.locales');
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getChangeLocaleUrl($locale)
    {
        /** @var Request $request */
        $request = $this->container->get('request_stack')->getMasterRequest();

        return $this->generator->generate('agile_kernel_change_locale', [
            'locale' => $locale,
            'r'      => $request->getRequestUri(),
        ]);
    }

    /**
     * @param array $query
     * @param int   $referenceType
     *
     * @return string
     */
    public function getUriReplaceQuery(array $query, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        /** @var Request $request */
        $request = $this->container->get('request_stack')->getMasterRequest();

        return $this->generator->generate(
            $request->attributes->get('_route'),
            array_merge($request->query->all(), $query),
            $referenceType
        );
    }

    /**
     * @param string $routeName
     *
     * @return string
     */
    public function getRouteExist($routeName)
    {
        $routes = $this->container->get('router')->getRouteCollection();

        return ($routes->get($routeName) === null) ? false : true;
    }

    /**
     * @return \DateTime
     */
    public function now()
    {
        return new \DateTime();
    }

    /**
     * @return array
     */
    public function getGlobals()
    {
        return [
            'agile' => $this->agileGlobals,
        ];
    }

    /**
     * @param \Twig_Environment $env
     * @param                   $date
     * @param string            $dateFormat
     * @param string            $timeFormat
     * @param null              $locale
     * @param null              $timezone
     * @param null              $format
     *
     * @return bool|string
     */
    public function localizedDateFilter(
        \Twig_Environment $env,
        $date,
        $dateFormat = 'medium',
        $timeFormat = 'medium',
        $locale = null,
        $timezone = null,
        $format = null
    ) {
        $date = twig_date_converter($env, $date, $timezone);

        $formatValues = [
            'none'   => \IntlDateFormatter::NONE,
            'short'  => \IntlDateFormatter::SHORT,
            'medium' => \IntlDateFormatter::MEDIUM,
            'long'   => \IntlDateFormatter::LONG,
            'full'   => \IntlDateFormatter::FULL,
        ];

        $formatter = \IntlDateFormatter::create(
            $locale,
            $formatValues[$dateFormat],
            $formatValues[$timeFormat],
            $date->getTimezone()->getName(),
            \IntlDateFormatter::GREGORIAN,
            $format
        );

        return $formatter->format($date->getTimestamp());
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function ucfirst($string)
    {
        return ucfirst($string);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function floor($string)
    {
        return floor($string);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function ceil($string)
    {
        return ceil($string);
    }

    /**
     * @param string $string
     *
     * @return boolean
     */
    public function isNumeric($string)
    {
        return is_numeric($string);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function jsonEncode($string)
    {
        return json_encode($string);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'agile_kernel';
    }
}
