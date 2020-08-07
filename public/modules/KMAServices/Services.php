<?php 
namespace KMA\Modules\KMAServices;

use KMA\Modules\KMAHelpers\CustomTaxonomy;
use KMA\Modules\KMAHelpers\CustomPostType;

class Services {

    public function __construct()
    {
        //blank on purpose
    }
    
    public function use()
    {
        
        new CustomPostType(
            'service', 
            'Services', 
            'Service', 
            'Services', 
            'excerpt-view', 
            [
                'title', 'editor', 'revisions', 'page-attributes', 'custom-fields'
            ], 
            true,
            'services'
        );
        // new CustomTaxonomy('service-group','Service Group', 'Service Groups', ['service'], true);

        if ( function_exists( 'acf_add_local_field_group' ) ) {
            add_action( 'init', [$this, 'registerFields'] );
        }     
        $this->setupEnpoints();
    }

    protected function setupShortcodes()
    {
        // add_shortcode( 'team', [$this, 'teamShortcode'] );
    }

    protected function setupEnpoints()
    {
        add_action('rest_api_init', [$this, 'addRoutes']);
    }

    /**
     * Add REST API routes
     */
    public function addRoutes()
    {
        register_rest_route(
            'kerigansolutions/v1',
            '/services',
            [
                'methods' => 'GET',
                'callback' => [$this, 'getServices']
            ]
        );
    }

    /**
     * Querys the database for custom post types
     * @param Number limit
     * @param String category
     * @return Array
     */
    public function query( $limit = -1, $category = '', $args = [] )
    {
        $request = [
            'posts_per_page' => $limit,
            'offset'         => 0,
            'order'          => 'ASC',
            'orderby'        => 'menu_order',
            'post_type'      => 'service',
            'post_status'    => 'publish',
        ];

        if ($category != '') {
            $categoryarray = [
                [
                    'taxonomy'         => 'service-group',
                    'field'            => 'slug',
                    'terms'            => $category,
                    'include_children' => false,
                ],
            ];
            $request['tax_query'] = $categoryarray;
        }

        $posts = get_posts(array_merge($request, $args));
        $output = [];
        foreach($posts as $post){
            $output[] = $this->addCustomFields($post);
        }

        return $output;
    }

    /**
     * Gets all the custom fields for the post and adds them to the post object
     * @param Object post
     * @return Object post
     */
    public function addCustomFields($post)
    {
        $post->intro_text     = get_field('into_text', $post->ID);
        $post->main_photo     = get_field('main_photo', $post->ID);
        $post->booking_url_1  = get_field('booking_url_1', $post->ID);
        $post->booking_url_2  = get_field('booking_url_2', $post->ID);
        $post->features       = get_field('trip_features', $post->ID);

        return $post;
    }

    public function registerFields()
    {
        acf_add_local_field_group(array(
            'key' => 'group_5e624cbf3da43',
            'title' => 'Service Details',
            'fields' => array(
                array(
                    'key' => 'field_5e624e005e637',
                    'label' => 'Intro Text',
                    'name' => 'into_text',
                    'type' => 'wysiwyg',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'tabs' => 'all',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'delay' => 0,
                ),
                array(
                    'key' => 'field_5e624cca70310',
                    'label' => 'Full Day Booking Url',
                    'name' => 'booking_url_1',
                    'type' => 'url',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_5e624liuw37f310',
                    'label' => 'Half Day Booking Url',
                    'name' => 'booking_url_2',
                    'type' => 'url',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_5e70f95a11ab6',
                    'label' => 'Trip Features',
                    'name' => 'trip_features',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => '',
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'row',
                    'button_label' => '',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5e70f97011ab7',
                            'label' => 'Feature',
                            'name' => 'feature',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_5e624cf670311',
                    'label' => 'Main Photo',
                    'name' => 'main_photo',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'large',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'service',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'acf_after_title',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));
    }

}