<?php

namespace KMA\Modules\KMAHelpers;

class ThemeFields extends \KeriganSolutions\KMAContactInfo\ContactInfo {

    public function use()
    {
        if ( function_exists( 'acf_add_local_field_group' ) ) {
            add_action( 'init', [$this, 'addGenericFields'] );
            add_action( 'init', [$this, 'addCustomFields'] );
            $this->setupShortcodes();
        }
    }

    public function setupShortcodes()
    {
        add_shortcode( 'email_address', [$this, 'emailShortcode'] );
        add_shortcode( 'phone_number', [$this, 'phoneShortcode'] );
        add_shortcode( 'toll_free_phone_number', [$this, 'tollFreePhoneShortcode'] );
        add_shortcode( 'fax_number', [$this, 'faxShortcode'] );
        add_shortcode( 'physical_address', [$this, 'addressShortcode'] );
        add_shortcode( 'hours', [$this, 'hoursShortcode'] );

        // add shortcode filter to ACF textarea fields
        add_filter('acf/format_value/type=textarea', 'do_shortcode');
    }

    public function emailShortcode($atts)
    {
        $a = shortcode_atts([
            'class' => 'contact-method',
            'icon'  => "false"
        ], $atts, 'email_address');

        return $this->formatEmailAddress('email', $a);
    }

    public function formatEmailAddress($key, $atts)
    {
        return '<span class="' . ($atts['class'] != '' ? 'contact-method '.$atts['class'].' ' : '') . '" >' .
        ($atts['icon'] == "true" ? '<i class="fa fa-envelope" ></i>' : '') .
        '<a class="contact-content" href="mailto:'.get_field($key,'option').'" >'.get_field($key,'option').'</a>' .
        '</span>';
    }

    public function addressShortcode($atts)
    {
        $a = shortcode_atts([
            'address'   => '1',
            'class'     => 'contact-method',
            'icon'      => "false",
            'multiline' => "true"
        ], $atts, 'physical_address');

        return $this->formatAddress('address_' . $a['address'], $a);
    }

    public function hoursShortcode($atts)
    {
        $a = shortcode_atts([
            'class'     => 'contact-method',
            'icon'      => "false",
            'multiline' => "true"
        ], $atts, 'hours');

        return $this->formatHours('hours', $a);
    }

    public function formatAddress($key, $atts)
    {
        if($atts['multiline'] == "true"){
            $address = nl2br(get_field($key,'option'));
        }else{
            $address = get_field($key,'option');
        }

        return '<span ' . ($atts['class'] != '' ? 'class="'.$atts['class'].'" ' : '') . '>' .
        ($atts['icon'] == "true" ? '<i class="fa fa-map-marker" ></i>' : '') .
        '<span class="contact-content" >' . $address . '</span></span>';
    }

    public function formatHours($key, $atts)
    {

        if($atts['multiline'] == "true"){
            $address = nl2br(get_field($key,'option'));
        }else{
            $address = get_field($key,'option');
        }

        return '<span ' . ($atts['class'] != '' ? 'class="'.$atts['class'].'" ' : '') . '>' .
        ($atts['icon'] == "true" ? '<i class="fa fa-clock-o" ></i>' : '') .
        '<span class="contact-content" >' . $address . '</span></span>';
    }

    public function phoneShortcode($atts)
    {
        $a = shortcode_atts([
            'class' => 'contact-method',
            'icon'  => ""
        ], $atts, 'phone_number');

        return $this->formatPhoneNumber('phone', $a);
    }

    public function faxShortcode($atts)
    {
        $a = shortcode_atts([
            'class' => 'contact-method',
            'icon'  => ""
        ], $atts, 'fax_number');

        return $this->formatFaxNumber('fax', $a);
    }

    public function formatFaxNumber($key, $atts)
    {
        return '<span ' . ($atts['class'] != '' ? 'class="'.$atts['class'].'" ' : '') . '>' .
        ($atts['icon'] != '' ? '<i class="fa fa-'.$atts['icon'].'"></i> ' : '') .
        get_field($key,'option') . '</span>';
    }

    public function formatPhoneNumber($key, $atts)
    {
        return '<span ' . ($atts['class'] != '' ? 'class="'.$atts['class'].'" ' : '') . '>' .
        ($atts['icon'] != '' ? '<i class="fa fa-'.$atts['icon'].'"></i> ' : '') .
        '<a class="contact-content" href="tel:'.get_field($key,'option').'" >' .
        get_field($key,'option') . '</a></span>';
    }

    public function addGenericFields()
    {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page(array(
                'page_title' => 'Website Theme Options',
                'menu_title' => 'Website Theme Options',
                'menu_slug' => 'theme-options',
                'capability' => 'edit_posts',
                'icon_url' => 'dashicons-info',
                'redirect' => false
            ));
        }

        //field group
        acf_add_local_field_group(array(
            'key' => 'group_contact_info',
            'title' => 'Contact info',
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-options',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
        ));

        // address
        acf_add_local_field(array(
            'key' => 'address_1',
            'label' => 'Physical Address',
            'name' => 'address_1',
            'type' => 'textarea',
            'wrapper' => array(
                'width' => '25',
                'class' => '',
                'id' => '',
            ),
            'parent' => 'group_contact_info',
        ));

        // address 2
        acf_add_local_field(array(
            'key' => 'address_2',
            'label' => 'Mailing Address',
            'name' => 'address_2',
            'type' => 'textarea',
            'wrapper' => array(
                'width' => '25',
                'class' => '',
                'id' => '',
            ),
            'parent' => 'group_contact_info',
        ));

        // acf_add_local_field(array(
        //     'key' => 'hours',
        //     'label' => 'Hours',
        //     'name' => 'hours',
        //     'type' => 'textarea',
        //     'wrapper' => array(
        //         'width' => '25',
        //         'class' => '',
        //         'id' => '',
        //     ),
        //     'parent' => 'group_contact_info',
        // ));

        // acf_add_local_field(array(
		// 	'key' => 'field_5e97ilub3of7hbub3ikrfjg24b',
		// 	'label' => 'Helpful Links Menu',
		// 	'name' => 'helpful_links',
		// 	'type' => 'repeater',
		// 	'instructions' => '',
		// 	'required' => 0,
		// 	'conditional_logic' => 0,
		// 	'wrapper' => array(
		// 		'width' => '25',
		// 		'class' => '',
		// 		'id' => '',
		// 	),
		// 	'collapsed' => '',
		// 	'min' => 0,
		// 	'max' => 0,
		// 	'layout' => 'table',
		// 	'button_label' => '',
		// 	'sub_fields' => array(
        //         array(
        //             'key' => 'field_5hny356hnrtgf224c',
        //             'label' => 'Page Link',
        //             'name' => 'page_link',
        //             'type' => 'link',
        //             'instructions' => '',
        //             'required' => 0,
        //             'conditional_logic' => 0,
        //             'wrapper' => array(
        //                 'width' => '',
        //                 'class' => '',
        //                 'id' => '',
        //             ),
        //             'return_format' => 'array',
        //         )
        //     ),
        //     'parent' => 'group_contact_info',
        // ));

        // acf_add_local_field(array(
		// 	'key' => 'field_5e97466cf224b',
		// 	'label' => 'Site Tools Menu',
		// 	'name' => 'site_tools',
		// 	'type' => 'repeater',
		// 	'instructions' => '',
		// 	'required' => 0,
		// 	'conditional_logic' => 0,
		// 	'wrapper' => array(
		// 		'width' => '25',
		// 		'class' => '',
		// 		'id' => '',
		// 	),
		// 	'collapsed' => '',
		// 	'min' => 0,
		// 	'max' => 0,
		// 	'layout' => 'table',
		// 	'button_label' => '',
		// 	'sub_fields' => array(
        //         array(
        //             'key' => 'field_5e974697f224c',
        //             'label' => 'Page Link',
        //             'name' => 'page_link',
        //             'type' => 'link',
        //             'instructions' => '',
        //             'required' => 0,
        //             'conditional_logic' => 0,
        //             'wrapper' => array(
        //                 'width' => '',
        //                 'class' => '',
        //                 'id' => '',
        //             ),
        //             'return_format' => 'array',
        //         )
        //     ),
        //     'parent' => 'group_contact_info',
        // ));


        // email
        acf_add_local_field(array(
            'key' => 'email',
            'label' => 'Email',
            'name' => 'email',
            'type' => 'text',
            'wrapper' => array(
                'width' => '20',
                'class' => '',
                'id' => '',
            ),
            'parent' => 'group_contact_info',
        ));

        // local phone
        acf_add_local_field(array(
            'key' => 'phone',
            'label' => 'Phone',
            'name' => 'phone',
            'type' => 'text',
            'wrapper' => array(
                'width' => '20',
                'class' => '',
                'id' => '',
            ),
            'parent' => 'group_contact_info',
        ));

        // toll-free phone
        acf_add_local_field(array(
            'key' => 'toll_free_phone',
            'label' => 'Toll Free Phone Number',
            'name' => 'toll_free_phone',
            'type' => 'text',
            'wrapper' => array(
                'width' => '20',
                'class' => '',
                'id' => '',
            ),
            'parent' => 'group_contact_info',
        ));

        // fax
        acf_add_local_field(array(
            'key' => 'fax',
            'label' => 'Fax',
            'name' => 'fax',
            'type' => 'text',
            'wrapper' => array(
                'width' => '20',
                'class' => '',
                'id' => '',
            ),
            'parent' => 'group_contact_info',
        ));



        acf_add_local_field(array(
            'key' => 'gtm_id',
            'label' => 'Google Tag Manager ID',
            'name' => 'gtm_id',
            'type' => 'text',
            'wrapper' => array(
                'width' => '20',
                'class' => '',
                'id' => '',
            ),
            'parent' => 'group_contact_info',
        ));

        // acf_add_local_field(array(
        //     'key' => 'header_banner_content',
        //     'label' => 'Alert / Sale Banner',
        //     'name' => 'header_banner_content',
        //     'type' => 'wysiwyg',
        //     'wrapper' => array(
        //         'width' => '',
        //         'class' => '',
        //         'id' => '',
        //     ),
        //     'parent' => 'group_contact_info',
        // ));

    }

    public function addCustomFields()
    {
    }
}
