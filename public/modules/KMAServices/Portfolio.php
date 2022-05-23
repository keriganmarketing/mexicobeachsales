<?php

namespace KMA\Modules\KMAServices;

class Portfolio {

    public $postName;
    public $postNamePlural;
    public $postType;

    public function use()
    {
        $this->postName = 'Project';
        $this->postNamePlural = 'Projects';
        $this->postType = 'project';

        // Create the post type
        add_action( 'init', [$this, 'createPostType'] );
        add_filter( 'post_updated_messages', [$this, 'postTypeUpdated'] );

        // Create Taxonomy
        add_action( 'init', [$this, 'createTaxonomy'] );
        add_filter( 'term_updated_messages', [$this, 'taxonomyUpdated'] );

        // Don't die if ACF isn't installed
        if ( function_exists( 'acf_add_local_field_group' ) ) {
            add_action( 'init', [$this, 'registerFields'] );
        }

        // Create REST API Routes
        add_action( 'rest_api_init', [$this, 'addRoutes'] );

        // Add shortcode
        add_shortcode( 'project_tiles', [$this, 'makeShortcode'] );
    }

    /**
     * Define shortcode content
     * returns a Vue component
     */
    public function makeShortcode($atts)
    {
        $a = shortcode_atts([
            'category' => '',
            'container_class' => 'row',
            'item_class' => ''
        ], $atts, 'project_tiles');

        ob_start(); ?>
        <project-tiles
            class="project-tiles <?php echo $a['container_class']; ?>"
            category="<?php echo $a['category']; ?>"
            item-class="<?php echo $a['item_class']; ?>"
        ></project-tiles>
        <?php return ob_get_clean();
    }

    /**
	 * Add REST API routes
	 */
    public function addRoutes()
    {
        register_rest_route( 'kerigansolutions/v1', '/projects',
            [
                'methods'  => 'GET',
                'callback' => [ $this, 'getPosts' ],
                'permission_callback' => '__return_true'
            ]
        );
    }

    /*
     * Get Areas using REST API endpoint
     */
    public function getPosts( $request )
    {
        $limit = $request->get_param( 'limit' );
        $category = $request->get_param( 'category' );
        return rest_ensure_response( $this->query($limit, $category));
    }

    public function registerFields()
    {
        acf_add_local_field_group(array(
            'key' => 'group_5e00308f10ef4',
            'title' => 'Tile Details',
            'fields' => array(
                array(
                    'key' => 'field_5e003101f4aaf',
                    'label' => 'Tile Photo',
                    'name' => 'tile_photo',
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
                    'preview_size' => 'medium',
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
                        'value' => 'project',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));


        acf_add_local_field_group(array(
            'key' => 'group_5e005f0eaccd7',
            'title' => 'Project Details',
            'fields' => array(
                array(
                    'key' => 'field_5dzewgbrtsjhnwr4eae0',
                    'label' => 'Project Location',
                    'name' => 'project_location',
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
                    'key' => 'field_5e0060b6003c0',
                    'label' => 'Project Photos',
                    'name' => 'project_photos',
                    'type' => 'gallery',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'insert' => 'append',
                    'library' => 'all',
                    'min' => '',
                    'max' => '',
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
                        'value' => 'project',
                    ),
                ),
            ),
            'menu_order' => 2,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));

    }

    /*
     * Query WP for post type
     */
    public function query($limit = -1, $category = null)
    {
        $request = [
            'posts_per_page' => $limit,
            'offset' => 0,
            'order' => 'ASC',
            'orderby' => 'menu_order',
            'post_type' => 'project',
            'post_status' => 'publish',
        ];

        if ($category != null) {
            $taxquery = [
                [
                    'taxonomy'         => 'project-category',
                    'field'            => 'slug',
                    'terms'            => $category,
                    'include_children' => false,
                ],
            ];
            $request['tax_query'] = $taxquery;
        }

        $posts = get_posts($request);

        $postArray = [];
        foreach ($posts as $post) {
            array_push($postArray, [
                'id' => (isset($post->ID) ? $post->ID : null),
                'name' => (isset($post->post_title) ? $post->post_title : null),
                'slug' => (isset($post->post_name) ? $post->post_name : null),
                'tile_photo' => get_field('tile_photo', $post->ID),
                'project_location' => get_field('project_location', $post->ID),
                'post_link' => get_permalink($post->ID),
            ]);
        }

        return $postArray;
    }

    /**
     * Registers the post type.
     */
    public function createPostType()
    {

        register_post_type( $this->postType, array(
            'labels'                => array(
                'name'                  => __( $this->postNamePlural, 'kerigansolutions' ),
                'singular_name'         => __( '' . $this->postName . '', 'kerigansolutions' ),
                'all_items'             => __( 'All ' . $this->postNamePlural . '', 'kerigansolutions' ),
                'archives'              => __( '' . $this->postName . ' Archives', 'kerigansolutions' ),
                'attributes'            => __( '' . $this->postName . ' Attributes', 'kerigansolutions' ),
                'insert_into_item'      => __( 'Insert into ' . $this->postName . '', 'kerigansolutions' ),
                'uploaded_to_this_item' => __( 'Uploaded to this ' . $this->postName . '', 'kerigansolutions' ),
                'featured_image'        => _x( 'Featured Image', $this->postType, 'kerigansolutions' ),
                'set_featured_image'    => _x( 'Set featured image', $this->postType, 'kerigansolutions' ),
                'remove_featured_image' => _x( 'Remove featured image', $this->postType, 'kerigansolutions' ),
                'use_featured_image'    => _x( 'Use as featured image', $this->postType, 'kerigansolutions' ),
                'filter_items_list'     => __( 'Filter ' . $this->postNamePlural . ' list', 'kerigansolutions' ),
                'items_list_navigation' => __( '' . $this->postNamePlural . ' list navigation', 'kerigansolutions' ),
                'items_list'            => __( '' . $this->postNamePlural . ' list', 'kerigansolutions' ),
                'new_item'              => __( 'New ' . $this->postName . '', 'kerigansolutions' ),
                'add_new'               => __( 'Add New', 'kerigansolutions' ),
                'add_new_item'          => __( 'Add New ' . $this->postName . '', 'kerigansolutions' ),
                'edit_item'             => __( 'Edit ' . $this->postName . '', 'kerigansolutions' ),
                'view_item'             => __( 'View ' . $this->postName . '', 'kerigansolutions' ),
                'view_items'            => __( 'View ' . $this->postNamePlural . '', 'kerigansolutions' ),
                'search_items'          => __( 'Search ' . $this->postNamePlural . '', 'kerigansolutions' ),
                'not_found'             => __( 'No ' . $this->postNamePlural . ' found', 'kerigansolutions' ),
                'not_found_in_trash'    => __( 'No ' . $this->postNamePlural . ' found in trash', 'kerigansolutions' ),
                'parent_item_colon'     => __( 'Parent ' . $this->postName . ':', 'kerigansolutions' ),
                'menu_name'             => __( $this->postNamePlural, 'kerigansolutions' ),
            ),
            'public'                => true,
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'supports'              => array( 'title','editor','page-attributes'),
            'has_archive'           => false,
            'rewrite'               => ['slug' => 'work'],
            'query_var'             => true,
            'menu_icon'             => 'dashicons-admin-site',
            'show_in_rest'          => true,
            'rest_base'             => 'work',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ) );
    }

    /**
     * Sets the post updated messages for the post type.
     *
     * @param  array $messages Post updated messages.
     * @return array Messages for the post type.
     */
    public function postTypeUpdated( $messages )
    {
        global $post;

        $permalink = get_permalink( $post );

        $messages[$this->postType] = array(
            0  => '', // Unused. Messages start at index 1.
            /* translators: %s: post permalink */
            1  => sprintf( __( '' . $this->postName . ' updated. <a target="_blank" href="%s">View ' . $this->postName . '</a>', 'kerigansolutions' ), esc_url( $permalink ) ),
            2  => __( 'Custom field updated.', 'kerigansolutions' ),
            3  => __( 'Custom field deleted.', 'kerigansolutions' ),
            4  => __( '' . $this->postName . ' updated.', 'kerigansolutions' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( __( '' . $this->postName . ' restored to revision from %s', 'kerigansolutions' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            /* translators: %s: post permalink */
            6  => sprintf( __( '' . $this->postName . ' published. <a href="%s">View ' . $this->postName . '</a>', 'kerigansolutions' ), esc_url( $permalink ) ),
            7  => __( '' . $this->postName . ' saved.', 'kerigansolutions' ),
            /* translators: %s: post permalink */
            8  => sprintf( __( '' . $this->postName . ' submitted. <a target="_blank" href="%s">Preview ' . $this->postName . '</a>', 'kerigansolutions' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
            /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
            9  => sprintf( __( '' . $this->postName . ' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview ' . $this->postName . '</a>', 'kerigansolutions' ),
            date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
            /* translators: %s: post permalink */
            10 => sprintf( __( '' . $this->postName . ' draft updated. <a target="_blank" href="%s">Preview ' . $this->postName . '</a>', 'kerigansolutions' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
        );

        return $messages;
    }

    /**
     * Registers the `Category` taxonomy,
     * for use with 'slide'.
     */
    public function createTaxonomy()
    {
        register_taxonomy( 'project-category', array( 'project' ), array(
            'hierarchical'      => true,
            'public'            => true,
            'show_in_nav_menus' => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => true,
            'capabilities'      => array(
                'manage_terms'  => 'edit_posts',
                'edit_terms'    => 'edit_posts',
                'delete_terms'  => 'edit_posts',
                'assign_terms'  => 'edit_posts',
            ),
            'labels'            => array(
                'name'                       => __( 'Categories', 'kerigansolutions' ),
                'singular_name'              => _x( 'Category', 'taxonomy general name', 'kerigansolutions' ),
                'search_items'               => __( 'Search Categories', 'kerigansolutions' ),
                'popular_items'              => __( 'Popular Categories', 'kerigansolutions' ),
                'all_items'                  => __( 'All Categories', 'kerigansolutions' ),
                'parent_item'                => __( 'Parent Category', 'kerigansolutions' ),
                'parent_item_colon'          => __( 'Parent Category:', 'kerigansolutions' ),
                'edit_item'                  => __( 'Edit Category', 'kerigansolutions' ),
                'update_item'                => __( 'Update Category', 'kerigansolutions' ),
                'view_item'                  => __( 'View Category', 'kerigansolutions' ),
                'add_new_item'               => __( 'New Category', 'kerigansolutions' ),
                'new_item_name'              => __( 'New Category', 'kerigansolutions' ),
                'separate_items_with_commas' => __( 'Separate Categories with commas', 'kerigansolutions' ),
                'add_or_remove_items'        => __( 'Add or remove categories', 'kerigansolutions' ),
                'choose_from_most_used'      => __( 'Choose from the most used categories', 'kerigansolutions' ),
                'not_found'                  => __( 'No Categories found.', 'kerigansolutions' ),
                'no_terms'                   => __( 'No Categories', 'kerigansolutions' ),
                'menu_name'                  => __( 'Categories', 'kerigansolutions' ),
                'items_list_navigation'      => __( 'Categories list navigation', 'kerigansolutions' ),
                'items_list'                 => __( 'Categories list', 'kerigansolutions' ),
                'most_used'                  => _x( 'Most Used', 'category', 'kerigansolutions' ),
                'back_to_items'              => __( '&larr; Back to Categories', 'kerigansolutions' ),
            ),
            'show_in_rest'      => true,
            'rest_base'         => 'project-category',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        ) );
    }

    /**
     * Sets the post updated messages for the `Category` taxonomy.
     *
     * @param  array $messages Post updated messages.
     * @return array Messages for the `Category` taxonomy.
     */
    public function taxonomyUpdated( $messages )
    {
        $messages['project-category'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => __( 'Category added.', 'kerigansolutions' ),
            2 => __( 'Category deleted.', 'kerigansolutions' ),
            3 => __( 'Category updated.', 'kerigansolutions' ),
            4 => __( 'Category not added.', 'kerigansolutions' ),
            5 => __( 'Category not updated.', 'kerigansolutions' ),
            6 => __( 'Categorys deleted.', 'kerigansolutions' ),
        );

        return $messages;
    }
}
