<?php

namespace KMA\Modules\KMAHelpers;

class CustomPostType
{

    public $postType;
    public $menuName;
    public $singularName;
    public $pluralName;
    public $menuIcon;
    public $supports;
    public $hierarchical;
    public $queryvar;

    /**
     * Creates a custom post type
     * @param String postType
     * @param String menuName
     * @param String singularName
     * @param String pluralName
     * @param String menuIcon
     * @param Array supports
     * @param Boolean hierarchical
     * @param String queryvar
     * @return null
     */
    public function __construct($postType, $menuName, $singularName, $pluralName, $menuIcon, $supports = ['title', 'editor', 'revisions', 'custom-fields'], $hierarchical = false, $queryvar = null)
    {
        $this->postType = $postType;
        $this->menuName = $menuName;
        $this->singularName = $singularName;
        $this->pluralName = $pluralName;
        $this->menuIcon = $menuIcon;
        $this->supports = $supports;
        $this->hierarchical = $hierarchical;
        $this->queryvar = ($queryvar != null ? $queryvar : $this->postType);

        add_action('init', [$this, 'create']);
        add_filter($this->postType . '_updated_messages', [$this, 'update']);

        // echo '<pre>',print_r($this),'</pre>';
    }

    public function create()
    {
        register_post_type($this->postType, array(
            'labels' => array(
                'name' => __($this->menuName, 'kerigansolutions'),
                'singular_name' => __($this->singularName, 'kerigansolutions'),
                'all_items' => __($this->menuName, 'kerigansolutions'),
                'archives' => __($this->menuName . ' Archives', 'kerigansolutions'),
                'attributes' => __($this->singularName . ' Attributes', 'kerigansolutions'),
                'insert_into_item' => __('Insert into ' . $this->singularName, 'kerigansolutions'),
                'uploaded_to_this_item' => __('Uploaded to this ' . $this->singularName, 'kerigansolutions'),
                'featured_image' => _x('Featured Image', $this->postType, 'kerigansolutions'),
                'set_featured_image' => _x('Set featured image', $this->postType, 'kerigansolutions'),
                'remove_featured_image' => _x('Remove featured image', $this->postType, 'kerigansolutions'),
                'use_featured_image' => _x('Use as featured image', $this->postType, 'kerigansolutions'),
                'filter_items_list' => __('Filter ' . $this->pluralName . ' list', 'kerigansolutions'),
                'items_list_navigation' => __($this->menuName . ' list navigation', 'kerigansolutions'),
                'items_list' => __($this->menuName . ' list', 'kerigansolutions'),
                'new_item' => __('New ' . $this->singularName, 'kerigansolutions'),
                'add_new' => __('Add New', 'kerigansolutions'),
                'add_new_item' => __('Add New ' . $this->singularName, 'kerigansolutions'),
                'edit_item' => __('Edit ' . $this->singularName, 'kerigansolutions'),
                'view_item' => __('View ' . $this->singularName, 'kerigansolutions'),
                'view_items' => __('View ' . $this->menuName, 'kerigansolutions'),
                'search_items' => __('Search ' . $this->menuName, 'kerigansolutions'),
                'not_found' => __('No ' . $this->pluralName . ' found', 'kerigansolutions'),
                'not_found_in_trash' => __('No ' . $this->pluralName . ' found in trash', 'kerigansolutions'),
                'parent_item_colon' => __('Parent ' . $this->singularName . ':', 'kerigansolutions'),
                'menu_name' => __($this->menuName, 'kerigansolutions'),
            ),
            'public' => true,
            'hierarchical' => $this->hierarchical,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'supports' => $this->supports,
            'has_archive' => $this->hierarchical,
            'rewrite' => [
                'slug' => $this->queryvar,
            ],
            'query_var' => $this->queryvar,
            'menu_icon' => 'dashicons-' . $this->menuIcon,
            'show_in_rest' => true,
            'rest_base' => $this->postType,
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ));
    }

    /**
     * Sets the post updated messages for the `project` post type.
     *
     * @param  array $messages Post updated messages.
     * @return array Messages for the `project` post type.
     */
    public function update($messages)
    {
        global $post;

        $permalink = get_permalink($post);

        $messages[$this->postType] = array(
            0 => '', // Unused. Messages start at index 1.
            /* translators: %s: post permalink */
            1 => sprintf(__($this->singularName . ' updated. <a target="_blank" href="%s">View ' . $this->singularName . '</a>', 'kerigansolutions'), esc_url($permalink)),
            2 => __('Custom field updated.', 'kerigansolutions'),
            3 => __('Custom field deleted.', 'kerigansolutions'),
            4 => __($this->singularName . ' updated.', 'kerigansolutions'),
            /* translators: %s: date and time of the revision */
            5 => isset($_GET['revision']) ? sprintf(__($this->singularName . ' restored to revision from %s', 'kerigansolutions'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
            /* translators: %s: post permalink */
            6 => sprintf(__($this->singularName . ' published. <a href="%s">View ' . $this->singularName . '</a>', 'kerigansolutions'), esc_url($permalink)),
            7 => __($this->singularName . ' saved.', 'kerigansolutions'),
            /* translators: %s: post permalink */
            8 => sprintf(__($this->singularName . ' submitted. <a target="_blank" href="%s">Preview ' . $this->singularName . '</a>', 'kerigansolutions'), esc_url(add_query_arg('preview', 'true', $permalink))),
            /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
            9 => sprintf(
                __($this->singularName . ' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview ' . $this->singularName . '</a>', 'kerigansolutions'),
                date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)),
                esc_url($permalink)
            ),
            /* translators: %s: post permalink */
            10 => sprintf(__($this->singularName . ' draft updated. <a target="_blank" href="%s">Preview ' . $this->singularName . '</a>', 'kerigansolutions'), esc_url(add_query_arg('preview', 'true', $permalink))),
        );

        return $messages;
    }
}
