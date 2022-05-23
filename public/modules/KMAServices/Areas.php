<?php

namespace KMA\Modules\KMAServices;

use KMA\Modules\KMAServices\Attractions;

class Areas {

    public function use()
    {
        // Create the post type for Area
        add_action( 'init', [$this, 'createPostType'] );
        add_filter( 'post_updated_messages', [$this, 'postTypeUpdated'] );

        // Don't die if ACF isn't installed
        if ( function_exists( 'acf_add_local_field_group' ) ) {
            add_action( 'init', [$this, 'registerFields'] );
        }

        // Create REST API Routes
        add_action( 'rest_api_init', [$this, 'addRoutes'] );

        (new Attractions)->use();
    }

    /*
     * Get Areas using REST API endpoint
     */
    public function getPosts( $request )
    {
        $limit = $request->get_param( 'limit' );
        return rest_ensure_response( $this->queryPosts($limit));
    }

    /**
	 * Add REST API routes
	 */
    public function addRoutes()
    {
        register_rest_route( 'kerigansolutions/v1', '/areas',
            [
                'methods'  => 'GET',
                'callback' => [ $this, 'getPosts' ],
                'permission_callback' => '__return_true'
            ]
        );
    }

    /**
	 * Check request permissions
     * Use for POST methods or for reading settings
	 *
	 * @return bool
	 */
	public function permissions(){
		return current_user_can( 'manage_options' ); // Administrators
    }

    /*
     * Query WP for posts
     */
    public function queryPosts( $limit = -1)
    {
        $request = [
            'posts_per_page' => $limit,
            'offset'         => 0,
            'order'          => 'ASC',
            'orderby'        => 'menu_order',
            'post_type'      => 'area',
            'post_status'    => 'publish',
        ];

        $posts = get_posts($request);

        $output = [];
        foreach ($posts as $post) {
            array_push($output, $post);
        }

        return $output;
    }

    public function registerFields()
    {

    }

    /**
     * Registers the `Area` post type.
     */
    public function createPostType()
    {

        register_post_type( 'area', array(
            'labels'                => array(
                'name'                  => __( 'Areas', 'kerigansolutions' ),
                'singular_name'         => __( 'Area', 'kerigansolutions' ),
                'all_items'             => __( 'All Areas', 'kerigansolutions' ),
                'archives'              => __( 'Area Archives', 'kerigansolutions' ),
                'attributes'            => __( 'Area Attributes', 'kerigansolutions' ),
                'insert_into_item'      => __( 'Insert into Area', 'kerigansolutions' ),
                'uploaded_to_this_item' => __( 'Uploaded to this Area', 'kerigansolutions' ),
                'featured_image'        => _x( 'Featured Image', 'Area', 'kerigansolutions' ),
                'set_featured_image'    => _x( 'Set featured image', 'Area', 'kerigansolutions' ),
                'remove_featured_image' => _x( 'Remove featured image', 'Area', 'kerigansolutions' ),
                'use_featured_image'    => _x( 'Use as featured image', 'Area', 'kerigansolutions' ),
                'filter_items_list'     => __( 'Filter Areas list', 'kerigansolutions' ),
                'items_list_navigation' => __( 'Areas list navigation', 'kerigansolutions' ),
                'items_list'            => __( 'Areas list', 'kerigansolutions' ),
                'new_item'              => __( 'New Area', 'kerigansolutions' ),
                'add_new'               => __( 'Add New', 'kerigansolutions' ),
                'add_new_item'          => __( 'Add New Area', 'kerigansolutions' ),
                'edit_item'             => __( 'Edit Area', 'kerigansolutions' ),
                'view_item'             => __( 'View Area', 'kerigansolutions' ),
                'view_items'            => __( 'View Areas', 'kerigansolutions' ),
                'search_items'          => __( 'Search Areas', 'kerigansolutions' ),
                'not_found'             => __( 'No Areas found', 'kerigansolutions' ),
                'not_found_in_trash'    => __( 'No Areas found in trash', 'kerigansolutions' ),
                'parent_item_colon'     => __( 'Parent Area:', 'kerigansolutions' ),
                'menu_name'             => __( 'Areas', 'kerigansolutions' ),
            ),
            'public'                => true,
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions'  ),
            'has_archive'           => true,
            'rewrite'               => ['slug' => 'areas'],
            'query_var'             => 'areas',
            'menu_icon'             => 'dashicons-location-alt',
            'show_in_rest'          => true,
            'rest_base'             => 'areas',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ) );
    }

    /**
     * Sets the post updated messages for the `Area` post type.
     *
     * @param  array $messages Post updated messages.
     * @return array Messages for the `Area` post type.
     */
    public function postTypeUpdated( $messages )
    {
        global $post;

        $permalink = get_permalink( $post );

        $messages['Area'] = array(
            0  => '', // Unused. Messages start at index 1.
            /* translators: %s: post permalink */
            1  => sprintf( __( 'Area updated. <a target="_blank" href="%s">View Area</a>', 'kerigansolutions' ), esc_url( $permalink ) ),
            2  => __( 'Custom field updated.', 'kerigansolutions' ),
            3  => __( 'Custom field deleted.', 'kerigansolutions' ),
            4  => __( 'Area updated.', 'kerigansolutions' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Area restored to revision from %s', 'kerigansolutions' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            /* translators: %s: post permalink */
            6  => sprintf( __( 'Area published. <a href="%s">View Area</a>', 'kerigansolutions' ), esc_url( $permalink ) ),
            7  => __( 'Area saved.', 'kerigansolutions' ),
            /* translators: %s: post permalink */
            8  => sprintf( __( 'Area submitted. <a target="_blank" href="%s">Preview Area</a>', 'kerigansolutions' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
            /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
            9  => sprintf( __( 'Area scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Area</a>', 'kerigansolutions' ),
            date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
            /* translators: %s: post permalink */
            10 => sprintf( __( 'Area draft updated. <a target="_blank" href="%s">Preview Area</a>', 'kerigansolutions' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
        );

        return $messages;
    }

}
