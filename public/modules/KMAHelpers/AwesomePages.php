<?php

namespace KMA\Modules\KMAHelpers;

class AwesomePages {
    public function use()
    {
        // Don't die if ACF isn't installed
        if ( function_exists( 'acf_add_local_field_group' ) ) {
            add_action( 'init', [$this, 'registerFields'] );
        }
    }

    public function registerFields()
    {

        // acf_add_local_field_group(array(
        //     'key' => 'group_5d1sdvsVDVSDVdea73b5',
        //     'title' => 'Page Header',
        //     'fields' => array(
        //         array(
        //             'key' => 'field_5dg45gedsvrw4geargadf',
        //             'label' => 'Header Image',
        //             'name' => 'header_image',
        //             'type' => 'image',
        //             'instructions' => '',
        //             'required' => 0,
        //             'conditional_logic' => 0,
        //             'wrapper' => array(
        //                 'width' => '100',
        //                 'class' => '',
        //                 'id' => '',
        //             ),
        //             'return_format' => 'array',
        //             'preview_size' => 'large',
        //             'library' => 'all',
        //             'min_width' => '',
        //             'min_height' => '',
        //             'min_size' => '',
        //             'max_width' => '',
        //             'max_height' => '',
        //             'max_size' => '',
        //             'mime_types' => '',
        //         )
        //     ),
        //     'location' => array(
        //         array(
        //             array(
        //                 'param' => 'post_type',
        //                 'operator' => '==',
        //                 'value' => 'page',
        //             ),
        //             array(
        //                 'param' => 'post_type',
        //                 'operator' => '==',
        //                 'value' => 'service',
        //             ),
        //             array(
        //                 'param' => 'page_type',
        //                 'operator' => '!=',
        //                 'value' => 'front_page',
        //             ),
        //         ),
        //     ),
        //     'menu_order' => 0,
        //     'position' => 'normal',
        //     'style' => 'default',
        //     'label_placement' => 'top',
        //     'instruction_placement' => 'label',
        //     'hide_on_screen' => '',
        //     'active' => 1,
        //     'description' => '',
        // ));

        acf_add_local_field_group(array(
            'key' => 'group_5d96a227',
            'title' => 'Page Fields',
            'fields' => array(
                array(
                    'key' => 'page_headline',
                    'label' => 'Page Headline (H1)',
                    'name' => 'page_headline',
                    'type' => 'text',
                    'wrapper' => array(
                        'width' => '100',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_5d965142076a1',
                    'label' => 'Show Sidebar',
                    'name' => 'show_sidebar',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 1,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
                array(
                    'key' => 'field_5d9ca4badb992',
                    'label' => 'Make Content Full Width?',
                    'name' => 'make_content_full_width',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_5d965142076a1',
                                'operator' => '!=',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
                array(
                    'key' => 'field_5d9ca4eadb993',
                    'label' => 'Hide page from other sidebar menus?',
                    'name' => 'hide_page_from_menus',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
                array(
                    'key' => 'field_5d9652a2076a2',
                    'label' => 'Menu Type',
                    'name' => 'menu_type',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_5d965142076a1',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'dynamic' => 'Dynamic Page List',
                        'custom' => 'Custom Page List',
                    ),
                    'allow_null' => 0,
                    'default_value' => 'dynamic',
                    'layout' => 'horizontal',
                    'return_format' => 'value',
                ),
                array(
                    'key' => 'field_5d9653a3076a3',
                    'label' => 'Page List',
                    'name' => 'page_list',
                    'type' => 'repeater',
                    'instructions' => 'Select pages you\'d like to include in the sidebar.',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_5d9652a2076a2',
                                'operator' => '==',
                                'value' => 'custom',
                            ),
                        ),
                    ),
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
                            'key' => 'field_5d9ca693a3c89',
                            'label' => 'Link',
                            'name' => 'link',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_5d965434076a4',
                    'label' => 'Additional Sidebar Content',
                    'name' => 'additional_sidebar_html',
                    'type' => 'wysiwyg',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_5d965142076a1',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 1,
                    'delay' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'service',
                    ),
                    array(
                        'param' => 'page_type',
                        'operator' => '!=',
                        'value' => 'front_page',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'page',
                    ),
                    array(
                        'param' => 'page_type',
                        'operator' => '!=',
                        'value' => 'front_page',
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

    public function hasChildren($id)
    {
        if( count( get_pages( array( 'child_of' => wp_get_post_parent_id($id) ) ) ) == 0 ) {
            return false;
        } else {
            return true;
        }
    }

    public function sidebarTitle( $id )
    {
        if($this->hasChildren($id)){
            return get_the_title(wp_get_post_parent_id($id));
        }else{
            return get_the_title($id);
        }
    }

    public function getPageList( $id )
    {

        $checkId = wp_get_post_parent_id($id);

        $args = array(
            'post_parent' => ($checkId),
            'post_type'   => get_post_type(),
            'numberposts' => -1,
            'post_status' => 'publish',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
            'meta_query'  => array(
                array(
                    'key'   => 'hide_page_from_menus',
                    'value' => '0',
                )
            )
        );
        $children = get_children( $args );

        $output = [];

        foreach($children as $child){
            $output[] = [
                'link'   => get_permalink($child->ID),
                'title'  => $child->post_title,
                'target' => '_self',
                'ID'     => $child->ID
            ];
        }

        return $output;
    }

    public function getSubPageList( $id )
    {

        $args = array(
            'post_parent' => ($id),
            'post_type'   => get_post_type(),
            'numberposts' => -1,
            'post_status' => 'publish',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
            'meta_query'  => array(
                array(
                    'key'   => 'hide_page_from_menus',
                    'value' => '0',
                )
            )
        );
        $children = get_children( $args );

        $output = [];

        foreach($children as $child){
            $output[] = [
                'link'   => get_permalink($child->ID),
                'title'  => $child->post_title,
                'target' => '_self',
                'ID'     => $child->ID
            ];
        }

        if(count($output) == 0) {
            return $this->getPageList( $id );
        }

        return $output;
    }

    public function getCustomMenu( $id )
    {
        $list = get_field('page_list', $id);

        $output = [];

        foreach($list as $child){

            $tempPost = get_page_by_title($child['link']['title']);
            $output[] = [
                'link'   => $child['link']['url'],
                'title'  => $child['link']['title'],
                'target' => $child['link']['target'] != '' ? $child['link']['target'] : '_self',
                'ID'     => $tempPost != null ? $tempPost->ID : ''
            ];
        }

        return $output;
    }

    public function getSidebar($id)
    {
        if(get_field('menu_type',$id) == 'dynamic'){
            return $this->getSubPageList($id);
        }else{
            return $this->getCustomMenu($id);
        }
    }
}
