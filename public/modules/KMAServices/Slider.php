<?php

namespace KMA\Modules\KMAServices;

use KMA\Modules\KMAHelpers\CustomTaxonomy;
use KMA\Modules\KMAHelpers\CustomPostType;

class Slider {


    public function __construct()
    {
        //blank on purpose
    }
    
    public function use()
    {
        
        new CustomPostType(
            'slide', 
            'Slides', 
            'Slide', 
            'Slides', 
            'slides', 
            [
                'title',
                'editor',
                'revisions',
                'custom-fields',
                'page-attributes'
            ]);
            
        new CustomTaxonomy(
            'slider',
            'Slider', 
            'Sliders', 
            'slide', 
            true);

        if ( function_exists( 'acf_add_local_field_group' ) ) {
            add_action( 'init', [$this, 'registerFields'] );
        }     
        
        add_shortcode( 'slider', [$this, 'sliderShortcode'] );
        add_action('rest_api_init', [$this, 'addRoutes']);
    }

    /**
     * Add REST API routes
     */
    public function addRoutes()
    {
        register_rest_route(
            'kerigansolutions/v1',
            '/sliders',
            [
                'methods' => 'GET',
                'callback' => [$this, 'getSlides']
            ]
        );
    }

    /* 
     * Get slides using REST API endpoint
     */
    public function getSlides( $request )
    {
        $limit    = $request->get_param( 'limit' );
        $category = $request->get_param( 'category' );
        return rest_ensure_response( $this->querySlides($limit, $category));
    }

    public function sliderShortcode($atts)
    {
        $a = shortcode_atts([
            'category' => 'homepage',
            'class'  => ""
        ], $atts, 'slider');

        ob_start(); ?>
        <kma-slider 
            class="slider-container <?php echo $a['class']; ?>" 
            category="<?php echo $a['category']; ?>"
        ></kma-slider>
        <?php return ob_get_clean();
    }

    /* 
     * Query WP for slides
     */
    public function querySlides( $limit = -1, $category = '' )
    {
        $request = [
            'posts_per_page' => $limit,
            'offset'         => 0,
            'order'          => 'ASC',
            'orderby'        => 'menu_order',
            'post_type'      => 'slide',
            'post_status'    => 'publish',
        ];

        if ($category != '') {
            $categoryarray = [
                [
                    'taxonomy'         => 'slider',
                    'field'            => 'slug',
                    'terms'            => $category,
                    'include_children' => false,
                ],
            ];
            $request['tax_query'] = $categoryarray;
        }

        $slidelist = get_posts($request);

        $slideArray = [];
        foreach ($slidelist as $slide) {
            array_push($slideArray, [
                'id'                          => (isset($slide->ID) ? $slide->ID : null),
                'name'                        => (isset($slide->post_title) ? $slide->post_title : null),
                'slug'                        => (isset($slide->post_name) ? $slide->post_name : null),
                'photo'                       => get_field('image',$slide->ID),
                'smartphone_photo'            => get_field('smartphone_image',$slide->ID),
                'image_alignment'             => get_field('image_alignment',$slide->ID),
                'image_fill_type'             => get_field('image_fill_type',$slide->ID),
                'content_justification'       => get_field('content_justification',$slide->ID),
                'content_alignment'           => get_field('content_alignment',$slide->ID),
                'smartphone_content_justification'  => get_field('smartphone_content_justification',$slide->ID),
                'smartphone_content_alignment' => get_field('smartphone_content_alignment',$slide->ID),
                'smartphone_image_alignment'  => get_field('smartphone_image_alignment',$slide->ID),
                'smartphone_image_fill_type'  => get_field('smartphone_image_fill_type',$slide->ID),
                'content'                     => $slide->post_content
            ]);
        }

        return $slideArray;
    }

    public function registerFields()
    {
        acf_add_local_field_group(array(
            'key' => 'group_5d0907d59a0b5',
            'title' => 'Slide Content',
            'fields' => array(
                array(
                    'key'           => 'image',
                    'label'         => 'Desktop Image',
                    'name'          => 'image',
                    'type'          => 'image',
                    'instructions'  => '',
                    'required'      => 0,
                    'return_format' => 'array',
                    'preview_size'  => 'large',
                    'library'       => 'all',
                    'min_width'     => 0,
                    'min_height'    => 0,
                    'max_width'     => 0,
                    'max_height'    => 0,
                ),
                array(
                    'key' => 'field_5e8745670ca86',
                    'label' => 'Image Alignment',
                    'name' => 'image_alignment',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'top left' => 'Top Left',
                        'bottom left' => 'Bottom Left',
                        'center' => 'Center',
                        'center bottom' => 'Center Bottom',
                        'center top' => 'Center Top',
                        'top right' => 'Top Right',
                        'bottom right' => 'Bottom Right',
                    ),
                    'default_value' => array(
                        0 => 'center',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'ajax' => 0,
                    'return_format' => 'value',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_5e8745860ca87',
                    'label' => 'Image Fill Type',
                    'name' => 'image_fill_type',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'contain' => 'Contain',
                        'cover' => 'Cover',
                        '100% auto' => 'Stretch Width',
                        'auto 100%' => 'Stretch Height',
                        '100% 100%' => 'Stretch Both',
                    ),
                    'default_value' => array(
                        0 => 'cover',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'ajax' => 0,
                    'return_format' => 'value',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_5lkikuabhesdgv78934gnb87',
                    'label' => 'Content Horizontal Alignment',
                    'name' => 'content_justification',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'justify-content-start' => 'Left',
                        'justify-content-center' => 'Center',
                        'justify-content-end' => 'Right'
                    ),
                    'default_value' => array(
                        0 => 'cover',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'ajax' => 0,
                    'return_format' => 'value',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_5lkbeara4vgergnb87',
                    'label' => 'Content Vertical Alignment',
                    'name' => 'content_alignment',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'align-items-start' => 'Top',
                        'align-items-center' => 'Middle',
                        'align-items-end' => 'Bottom'
                    ),
                    'default_value' => array(
                        0 => 'cover',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'ajax' => 0,
                    'return_format' => 'value',
                    'placeholder' => '',
                ),
                array(
                    'key'           => 'smartphone_image',
                    'label'         => 'Smartphone Image',
                    'name'          => 'smartphone_image',
                    'type'          => 'image',
                    'instructions'  => '',
                    'required'      => 0,
                    'return_format' => 'array',
                    'preview_size'  => 'large',
                    'library'       => 'all',
                    'min_width'     => 0,
                    'min_height'    => 0,
                    'max_width'     => 0,
                    'max_height'    => 0,
                ),
                array(
                    'key' => 'field_5ilvunoq43w75ghnuja86',
                    'label' => 'Smartphone Image Alignment',
                    'name' => 'smartphone_image_alignment',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'top left' => 'Top Left',
                        'bottom left' => 'Bottom Left',
                        'center' => 'Center',
                        'center bottom' => 'Center Bottom',
                        'center top' => 'Center Top',
                        'top right' => 'Top Right',
                        'bottom right' => 'Bottom Right',
                    ),
                    'default_value' => array(
                        0 => 'center',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'ajax' => 0,
                    'return_format' => 'value',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_5e89485hguhb87340ca87',
                    'label' => 'Smartphone Image Fill Type',
                    'name' => 'smartphone_image_fill_type',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'contain' => 'Contain',
                        'cover' => 'Cover',
                        '100% auto' => 'Stretch Width',
                        'auto 100%' => 'Stretch Height',
                        '100% 100%' => 'Stretch Both',
                    ),
                    'default_value' => array(
                        0 => 'cover',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'ajax' => 0,
                    'return_format' => 'value',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_5lkabe4rgrfdbge3a7',
                    'label' => 'Smartphone Content Horizontal Alignment',
                    'name' => 'smartphone_content_justification',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'align-items-start' => 'Left',
                        'align-items-center' => 'Center',
                        'align-items-end' => 'Right'
                    ),
                    'default_value' => array(
                        0 => 'cover',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'ajax' => 0,
                    'return_format' => 'value',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_5lbwre5b6ret6yb487',
                    'label' => 'Smartphone Content Vertical Alignment',
                    'name' => 'smartphone_content_alignment',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'align-items-start' => 'Top',
                        'align-items-center' => 'Middle',
                        'align-items-end' => 'Bottom'
                    ),
                    'default_value' => array(
                        0 => 'cover',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'ajax' => 0,
                    'return_format' => 'value',
                    'placeholder' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'slide',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ));
        
    }

}