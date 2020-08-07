<?php

namespace KMA\Modules\KMAServices;

class Attractions {

    public function use()
    {
        // Create the post type for Attraction
        add_action( 'init', [$this, 'createPostType'] );
        add_filter( 'post_updated_messages', [$this, 'postTypeUpdated'] );
        add_filter( 'manage_attraction_posts_columns', [$this, 'setPostColumns'] );
        add_action( 'manage_attraction_posts_custom_column', [$this, 'setColumnContent'], 10, 2);

        // Don't die if ACF isn't installed
        if ( function_exists( 'acf_add_local_field_group' ) ) {
            add_action( 'init', [$this, 'registerFields'] );
        }

        // Create REST API Routes
        add_action( 'rest_api_init', [$this, 'addRoutes'] );
    }

    public function setPostColumns()
    {
        return [
            'cb'         => '<input type="checkbox" />',
            'title'      => 'Title',
            'area'       => 'Area',
            'type'       => 'Type',
            'gps_coords' => 'Lat / Lng',
            'date'       => 'Date'
        ];
    }

    public function setColumnContent( $column, $post_id )
    {
        if ( 'area' === $column ) {
            echo get_field('area',$post_id);
        }
        if ( 'type' === $column ) {
            echo get_field('type',$post_id);
        }
        if ( 'gps_coords' === $column ) {
            echo get_field('gps_coords',$post_id);
        }
    }

/* 
     * Get Attractions using REST API endpoint
     */
    public function getPosts( $request )
    {
        $limit   = ($request->get_param( 'limit' ) != '' ? $request->get_param( 'limit' ) : -1);
        $filters = ($request->get_param( 'filters' ) != '' ? $request->get_param( 'filters' ) : []);
        $relation = ($request->get_param( 'relation' ) != '' ? $request->get_param( 'relation' ) : 'AND');
        $attractions = $this->queryPosts($limit, $filters, $relation);

        return rest_ensure_response( $attractions );
    }

    /**
	 * Add REST API routes
	 */
    public function addRoutes() 
    {
        register_rest_route( 'kerigansolutions/v1', '/attractions',
            [
                'methods'  => 'GET',
                'callback' => [ $this, 'getPosts' ]
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
    public function queryPosts( $limit = -1, $filters = [], $relation = 'AND')
    {
        $request = [
            'posts_per_page' => $limit,
            'offset'         => 0,
            'order'          => 'ASC',
            'orderby'        => 'menu_order',
            'post_type'      => 'Attraction',
            'post_status'    => 'publish',
        ];

        if(is_array($filters) && (count($filters) > 0)){
            $meta = [
                'relation' => $relation
            ];

            foreach($filters as $key => $var) {
                $meta[] = [
                    'key'	 	=> $key,
                    'value'	  	=> $var,
                    'compare' 	=> '==',
                ];
                $request['meta_query'] = [$meta];
            }
        }

        // echo '<pre>',print_r($request),'</pre>';

        $posts = get_posts($request);

        $output = [];
        foreach ($posts as $post) {
            $post->area = get_field('area',$post->ID);
            $post->type = get_field('type',$post->ID);
            $post->address = get_field('address',$post->ID);
            $post->phone_number = get_field('phone_number',$post->ID);
            $post->toll_free_phone_number = get_field('toll_free_phone_number',$post->ID);
            $post->fax_number = get_field('fax_number',$post->ID);
            $post->email_address = get_field('email_address',$post->ID);
            $post->website = get_field('website',$post->ID);
            
            $coords = explode(',', get_field('gps_coords', $post->ID));
            if(is_array($coords) && count($coords) > 1){
                $post->latitude = trim($coords[0]);
                $post->longitude = trim($coords[1]);
                array_push($output, $post);
            }
            

            // echo '<pre>',print_r($post),'</pre>';

        }

        return $output;
    }

    public function registerFields()
    {
        acf_add_local_field_group(array(
            'key' => 'group_5bb3d48fd4fe4',
            'title' => 'Contact Info',
            'fields' => array(
                array(
                    'key' => 'field_5dcd9a9996086',
                    'label' => 'Area',
                    'name' => 'area',
                    'type' => 'text',
                    'instructions' => 'Type the area this attraction should be attached to.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'area',
                    ),
                    'taxonomy' => '',
                    'allow_null' => 0,
                ),
                array(
                    'key' => 'field_5defb007aeadb',
                    'label' => 'Type',
                    'name' => 'type',
                    'type' => 'select',
                    'instructions' => 'Select the Type of Attraction (for map icon).',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'Baytowne' => 'Baytowne',
                        'Information' => 'Information',
                        'Medical' => 'Medical',
                        'Dining' => 'Dining',
                        'Shopping' => 'Shopping',
                        'Amenities' => 'Amenities',
                        'Boat Launch' => 'Boat Launch',
                    ),
                    'default_value' => array(
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'ajax' => 1,
                    'return_format' => 'value',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_5bb3d49a7ebb3',
                    'label' => 'Address',
                    'name' => 'address',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '3',
                    'new_lines' => '',
                ),
                array(
                    'key' => 'spacer',
                    'label' => '',
                    'name' => '',
                    'type' => 'spacer',
                    'wrapper' => array(
                        'width' => '100',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_5bb3d50f08640',
                    'label' => 'Phone Number',
                    'name' => 'phone_number',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5bb3d53c9c0a1',
                    'label' => 'Toll Free Phone Number',
                    'name' => 'toll_free_phone_number',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5bb3d54a9093d',
                    'label' => 'Fax Number',
                    'name' => 'fax_number',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5bb3d553694f8',
                    'label' => 'Email Address',
                    'name' => 'email_address',
                    'type' => 'email',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_5bb3d565fd977',
                    'label' => 'Website',
                    'name' => 'website',
                    'type' => 'url',
                    'instructions' => '',
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
                    'key' => 'field_sdvsdvsvawreb',
                    'label' => 'Latitude, Longitude',
                    'name' => 'gps_coords',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'spacer2',
                    'label' => '',
                    'name' => '',
                    'type' => 'spacer',
                    'wrapper' => array(
                        'width' => '100',
                        'class' => '',
                        'id' => '',
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'attraction',
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

    /**
     * Registers the `Attraction` post type.
     */
    public function createPostType()
    {
        
        register_post_type( 'attraction', array(
            'labels'                => array(
                'name'                  => __( 'Attractions', 'kerigansolutions' ),
                'singular_name'         => __( 'Attraction', 'kerigansolutions' ),
                'all_items'             => __( 'All Attractions', 'kerigansolutions' ),
                'archives'              => __( 'Attraction Archives', 'kerigansolutions' ),
                'attributes'            => __( 'Attraction Attributes', 'kerigansolutions' ),
                'insert_into_item'      => __( 'Insert into Attraction', 'kerigansolutions' ),
                'uploaded_to_this_item' => __( 'Uploaded to this Attraction', 'kerigansolutions' ),
                'featured_image'        => _x( 'Featured Image', 'Attraction', 'kerigansolutions' ),
                'set_featured_image'    => _x( 'Set featured image', 'Attraction', 'kerigansolutions' ),
                'remove_featured_image' => _x( 'Remove featured image', 'Attraction', 'kerigansolutions' ),
                'use_featured_image'    => _x( 'Use as featured image', 'Attraction', 'kerigansolutions' ),
                'filter_items_list'     => __( 'Filter Attractions list', 'kerigansolutions' ),
                'items_list_navigation' => __( 'Attractions list navigation', 'kerigansolutions' ),
                'items_list'            => __( 'Attractions list', 'kerigansolutions' ),
                'new_item'              => __( 'New Attraction', 'kerigansolutions' ),
                'add_new'               => __( 'Add New', 'kerigansolutions' ),
                'add_new_item'          => __( 'Add New Attraction', 'kerigansolutions' ),
                'edit_item'             => __( 'Edit Attraction', 'kerigansolutions' ),
                'view_item'             => __( 'View Attraction', 'kerigansolutions' ),
                'view_items'            => __( 'View Attractions', 'kerigansolutions' ),
                'search_items'          => __( 'Search Attractions', 'kerigansolutions' ),
                'not_found'             => __( 'No Attractions found', 'kerigansolutions' ),
                'not_found_in_trash'    => __( 'No Attractions found in trash', 'kerigansolutions' ),
                'parent_item_colon'     => __( 'Parent Attraction:', 'kerigansolutions' ),
                'menu_name'             => __( 'Attractions', 'kerigansolutions' ),
            ),
            'public'                => false,
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_in_nav_menus'     => false,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
            'has_archive'           => false,
            'rewrite'               => false,
            'query_var'             => false,
            'menu_icon'             => 'dashicons-location',
            'show_in_rest'          => true,
            'rest_base'             => 'attraction',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ) );
    }

    /**
     * Sets the post updated messages for the `Attraction` post type.
     *
     * @param  array $messages Post updated messages.
     * @return array Messages for the `Attraction` post type.
     */
    public function postTypeUpdated( $messages )
    {
        global $post;

        $permalink = get_permalink( $post );

        $messages['Attraction'] = array(
            0  => '', // Unused. Messages start at index 1.
            /* translators: %s: post permalink */
            1  => sprintf( __( 'Attraction updated. <a target="_blank" href="%s">View Attraction</a>', 'kerigansolutions' ), esc_url( $permalink ) ),
            2  => __( 'Custom field updated.', 'kerigansolutions' ),
            3  => __( 'Custom field deleted.', 'kerigansolutions' ),
            4  => __( 'Attraction updated.', 'kerigansolutions' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Attraction restored to revision from %s', 'kerigansolutions' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            /* translators: %s: post permalink */
            6  => sprintf( __( 'Attraction published. <a href="%s">View Attraction</a>', 'kerigansolutions' ), esc_url( $permalink ) ),
            7  => __( 'Attraction saved.', 'kerigansolutions' ),
            /* translators: %s: post permalink */
            8  => sprintf( __( 'Attraction submitted. <a target="_blank" href="%s">Preview Attraction</a>', 'kerigansolutions' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
            /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
            9  => sprintf( __( 'Attraction scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Attraction</a>', 'kerigansolutions' ),
            date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
            /* translators: %s: post permalink */
            10 => sprintf( __( 'Attraction draft updated. <a target="_blank" href="%s">Preview Attraction</a>', 'kerigansolutions' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
        );

        return $messages;
    }
}