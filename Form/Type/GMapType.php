<?php
namespace AgileKernelBundle\Form\Type;

use AgileKernelBundle\Assets\AssetsStack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Class GMapType
 *
 * @package AgileKernelBundle\Form\Type
 */
class GMapType extends AbstractType
{
    /**
     * @var AssetsStack
     */
    public $assetsStack;

    static public $assetsIncluded = false;

    /**
     * GMapType constructor.
     *
     * @param AssetsStack $assetsStack
     */
    public function __construct(AssetsStack $assetsStack, $googleMapApiKey)
    {
        $this->assetsStack = $assetsStack;

        if (false === self::$assetsIncluded) {
            self::$assetsIncluded = true;
            $this->assetsStack->appendJavascriptInclude('//maps.googleapis.com/maps/api/js?key='.$googleMapApiKey.'&libraries=places&sensor=true');
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $addHidden = function ($name) use ($builder) {
            $builder->add($name, HiddenType::class, []);

            return $this;
        };
        $builder->add('formatted_address', TextType::class, array_merge_recursive([
            'translation_domain' => $options['translation_domain'],
            'attr'               => [
                'placeholder' => 'form.gmap.autocomplete.placeholder',
            ],
            'label'              => false,
        ], $options['text_options']));
        $addHidden('street_number');
        $addHidden('route');
        $addHidden('locality');
        $addHidden('postal_code');
        $addHidden('administrative_area_level_2');
        $addHidden('administrative_area_level_1');
        $addHidden('country');
        $addHidden('latitude');
        $addHidden('longitude');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'text_options'       => [],
            'translation_domain' => 'AgileKernelBundle',
            'compound'           => true,
            'error_mapping'      => [
                '.' => 'formatted_address',
            ],
        ]);
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
        return 'agile_gmap';
    }
}
