<?php
namespace KMA\Modules\KMAServices;

use KMA\Modules\KMAHelpers\CustomTaxonomy;
use KMA\Modules\KMAHelpers\CustomPostType;

class Floorplans {

    public function __construct()
    {
        //blank on purpose
    }

    public function use()
    {

        new CustomPostType(
            'plan',
            'Floorplans',
            'Floorplan',
            'Floorplans',
            'screenoptions',
            [
                'title',
                'editor',
                'revisions',
                'custom-fields',
                'page-attributes'
            ]);
        new CustomTaxonomy(
            'plan-group',
            'Group',
            'Groups',
            'plan',
            true);

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
            '/floorplans',
            [
                'methods' => 'GET',
                'callback' => [$this, 'getFloorplans'],
                'permission_callback' => '__return_true'
            ]
        );
    }

    public function registerFields()
    {

    }

    /**
     * Querys the database for custom post types
     * @param Number limit
     * @param String category
     * @return Array
     */
    public function query( $limit = -1, $category = '', $orderby = 'menu_order', $order = 'DESC' )
    {
        $request = [
            'posts_per_page' => $limit,
            'offset'         => 0,
            'order'          => $order,
            'orderby'        => $orderby,
            'post_type'      => 'plan',
            'post_status'    => 'publish',
        ];

        if ($category != '') {
            $categoryarray = [
                [
                    'taxonomy'         => 'plan-group',
                    'field'            => 'slug',
                    'terms'            => $category,
                    'include_children' => false,
                ],
            ];
            $request['tax_query'] = $categoryarray;
        }

        // echo '<pre>',print_r($request),'</pre>';

        $output = [];

        foreach(get_posts($request) as $post){
            $post->bedrooms = get_field('number_of_bedrooms', $post->ID);
            $post->bathrooms = get_field('number_of_bathrooms', $post->ID);
            $post->sqft = get_field('square_feet', $post->ID);
            $post->render = get_field('render', $post->ID);
            $post->gallery = get_field('photo_gallery', $post->ID);
            $post->link = get_the_permalink($post->ID);

            $output[] = $post;
        }

        return $output;
    }

    public function getRand( $category )
    {
        return ($this->query(1, $category, 'rand'))[0];
    }

    public function getFloorplans( $request )
    {
        $limit    = $request->get_param( 'limit' ) != '' ? $request->get_param( 'limit' ) : -1;
        $category = $request->get_param( 'category' ) != '' ? $request->get_param( 'category' ) : '';
        $order    = $request->get_param( 'order' ) != '' ? $request->get_param( 'order' ) : 'ASC';
        $orderby  = $request->get_param( 'orderby' ) != '' ? $request->get_param( 'orderby' ) : 'menu_order';
        return rest_ensure_response( $this->query($limit, $category, $orderby, $order));
    }
}
