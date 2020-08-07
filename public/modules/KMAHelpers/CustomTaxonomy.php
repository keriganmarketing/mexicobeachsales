<?php

namespace KMA\Modules\KMAHelpers;

class CustomTaxonomy {

    public $postType;
    public $singularName;
    public $pluralName;
    public $menuIcon;
    public $hierarchical;
    public $public;
    public $rewrite;
    public $queryvar;

    /**
     * Creates a custom taxonomy
     * @param String taxonomy
     * @param String singularName
     * @param String pluralName
     * @param Array postType
     * @param Boolean hierarchical optional
     * @param Boolean public optional
     * @param Boolean rewrite optional
     * @return null
     */
    public function __construct( $taxonomy, $singularName, $pluralName, $postType, $hierarchical = false, $public = true, $rewrite = false, $queryvar = false ){
        $this->taxonomy = $taxonomy;
        $this->postType = $postType;
        $this->hierarchical = $hierarchical;
        $this->singularName = $singularName;
        $this->pluralName = $pluralName;
        $this->public = $public;
        $this->rewrite = $rewrite;
        $this->queryvar = $queryvar;

        add_action( 'init', [$this, 'create'] );
        add_filter( $taxonomy . '_updated_messages', [$this, 'update'] );
    }

    public function create()
    {
        register_taxonomy($this->taxonomy, $this->postType, array(
            'hierarchical'      => $this->hierarchical,
            'public'            => $this->public,
            'show_in_nav_menus' => true,
            'show_ui'           => true,
            'show_admin_column' => false,
            'query_var'         => $this->queryvar,
            'rewrite'           => $this->rewrite,
            'capabilities'      => array(
                'manage_terms'  => 'edit_posts',
                'edit_terms'    => 'edit_posts',
                'delete_terms'  => 'edit_posts',
                'assign_terms'  => 'edit_posts',
            ),
            'labels'            => array(
                'name'                       => __($this->pluralName, 'kerigansolutions'),
                'singular_name'              => _x($this->singularName, 'taxonomy general name', 'kerigansolutions'),
                'search_items'               => __('Search ' . $this->pluralName, 'kerigansolutions'),
                'popular_items'              => __('Popular ' . $this->pluralName, 'kerigansolutions'),
                'all_items'                  => __('All ' . $this->pluralName, 'kerigansolutions'),
                'parent_item'                => __('Parent ' . $this->singularName, 'kerigansolutions'),
                'parent_item_colon'          => __('Parent Department:', 'kerigansolutions'),
                'edit_item'                  => __('Edit ' . $this->singularName, 'kerigansolutions'),
                'update_item'                => __('Update ' . $this->singularName, 'kerigansolutions'),
                'view_item'                  => __('View ' . $this->singularName, 'kerigansolutions'),
                'add_new_item'               => __('New ' . $this->singularName, 'kerigansolutions'),
                'new_item_name'              => __('New ' . $this->singularName, 'kerigansolutions'),
                'separate_items_with_commas' => __('Separate ' . $this->pluralName . ' with commas', 'kerigansolutions'),
                'add_or_remove_items'        => __('Add or remove ' . $this->pluralName, 'kerigansolutions'),
                'choose_from_most_used'      => __('Choose from the most used ' . $this->pluralName, 'kerigansolutions'),
                'not_found'                  => __('No ' . $this->pluralName . 's found.', 'kerigansolutions'),
                'no_terms'                   => __('No ' . $this->pluralName, 'kerigansolutions'),
                'menu_name'                  => __($this->pluralName, 'kerigansolutions'),
                'items_list_navigation'      => __($this->pluralName . ' list navigation', 'kerigansolutions'),
                'items_list'                 => __($this->pluralName . ' list', 'kerigansolutions'),
                'most_used'                  => _x('Most Used', $this->singularName, 'kerigansolutions'),
                'back_to_items'              => __('&larr; Back to ' . $this->pluralName, 'kerigansolutions'),
            ),
            'show_in_rest'          => true,
            'rest_base'             => $this->taxonomy,
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        ));

    }

    /**
     * Sets the post updated messages for the $this->taxonomy taxonomy.
     *
     * @param  array $messages Post updated messages.
     * @return array Messages for the $this->taxonomy taxonomy.
     */
    public function update($messages)
    {
        $messages[$this->taxonomy] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => __($this->singularName . ' added.', 'kerigansolutions'),
            2 => __($this->singularName . ' deleted.', 'kerigansolutions'),
            3 => __($this->singularName . ' updated.', 'kerigansolutions'),
            4 => __($this->singularName . ' not added.', 'kerigansolutions'),
            5 => __($this->singularName . ' not updated.', 'kerigansolutions'),
            6 => __($this->pluralName . ' deleted.', 'kerigansolutions'),
        );

        return $messages;
    }

}