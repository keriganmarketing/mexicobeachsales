<?php

namespace KMA\Modules\KMAHelpers;

class Plate {

    public function __construct()
    {
    }

    public function use()
    {

        // Set custom footer text.
        add_theme_support(
            'plate-footer-text', 
            'Thank you for creating with <a href="https://keriganmarketing.com" target="_blank" rel="noopener noreferrer" >Kerigan Marketing Associates</a>.'
        );

        // Disable dashboard widgets.
        add_theme_support('plate-disable-dashboard', [
            'dashboard_activity',
            'dashboard_incoming_links',
            'dashboard_plugins',
            'dashboard_recent_comments',
            'dashboard_primary',
            'dashboard_quick_press',
            'dashboard_recent_drafts',
            'dashboard_secondary',
        ]);

        // Disable meta boxes in editor.
        add_theme_support('plate-disable-editor', [
            'commentsdiv',
            'commentstatusdiv',
            'linkadvanceddiv',
            'linktargetdiv',
            'linkxfndiv',
            'postcustom',
            'postexcerpt',
            'revisionsdiv',
            'slugdiv',
            'sqpt-meta-tags',
            'trackbacksdiv',
            //'categorydiv',
            //'tagsdiv-post_tag',
        ]);

        // Disable links from admin toolbar.
        add_theme_support('plate-disable-toolbar', [
            'archive',
            'comments',
            'wp-logo',
            'edit',
            'appearance',
            'view',
            'new-content',
            'updates',
            'search',
        ]);

        if(current_theme_supports('plate-disable-dashboard')){
            add_action('after_setup_theme', [$this, 'hideDashboardwidgets'], 100);
        }

        if(current_theme_supports('plate-footer-text')){
            add_action('after_setup_theme', [$this, 'setCustomFooterText'], 100);
        }

        // if(current_theme_supports('plate-disable-api')){
        //     add_action('after_setup_theme', [$this, 'addRestFilter'], 100);
        // }

        if(current_theme_supports('plate-disable-editor')){
            add_action('after_setup_theme', [$this, 'removeMetaBoxes'], 100);
        }

        // if(current_theme_supports('plate-disable-toolbar')){
        //     add_action('after_setup_theme', [$this, 'removeAdminBarItems'], 100);
        // }

            // require_if_theme_supports('plate-disable-gutenberg', __DIR__.'/src/disable-gutenberg.php');
            // require_if_theme_supports('plate-disable-menu', __DIR__.'/src/disable-menu.php');
            // require_if_theme_supports('plate-disable-tabs', __DIR__.'/src/disable-tabs.php');
            // require_if_theme_supports('plate-disable-toolbar', __DIR__.'/src/disable-toolbar.php');
            // require_if_theme_supports('plate-footer-text', __DIR__.'/src/footer-text.php');
            // require_if_theme_supports('plate-login-logo', __DIR__.'/src/login-logo.php');
            // require_if_theme_supports('plate-permalink', __DIR__.'/src/permalink.php');
    }

    /**
     * Require authentication for all requests for the REST API.
     * @return null
     */
    public function addRestFilter()
    {
        add_filter('rest_authentication_errors', function ($result) {
            if (!empty($result)) {
                return $result;
            }

            if (!is_user_logged_in()) {
                return new \WP_Error('rest_not_logged_in', 'You are not currently logged in.', ['status' => 401]);
            }

            return $result;
        });
    }

    /**
    * Removes dashboard widgets 
    * @return null
    */
    public function hideDashboardwidgets()
    {
        add_action('wp_dashboard_setup', function () {
            global $wp_meta_boxes;

            $positions = [
                'dashboard_activity' => 'normal',
                'dashboard_incoming_links' => 'normal',
                'dashboard_plugins' => 'normal',
                'dashboard_recent_comments' => 'normal',
                'dashboard_right_now' => 'normal',
                'dashboard_primary' => 'side',
                'dashboard_quick_press' => 'side',
                'dashboard_recent_drafts' => 'side',
                'dashboard_secondary' => 'side',
            ];

            $boxes = get_theme_support('plate-disable-dashboard')[0];

            foreach ($boxes as $box) {
                $position = $positions[$box] ?: 'normal';

                unset($wp_meta_boxes['dashboard'][$position]['core'][$box]);
            }
        });
    }

    /**
     * Removes specific meta boxes in the block editor
     * @return null
     */
    public function removeMetaBoxes()
    {
        add_action('admin_menu', function () {
            $types = [
                'categorydiv' => 'post',
                'commentsdiv' => 'post',
                'commentstatusdiv' => 'post',
                'linkadvanceddiv' => 'link',
                'linktargetdiv' => 'link',
                'linkxfndiv' => 'link',
                'postcustom' => 'post',
                'postexcerpt' => 'post',
                'revisionsdiv' => 'post',
                'slugdiv' => 'post',
                'sqpt-meta-tags' => 'post',
                'tagsdiv-post_tag' => 'post',
                'trackbacksdiv' => 'post',
            ];
        
            $boxes = get_theme_support('plate-disable-editor')[0];
        
            foreach ($boxes as $box) {
                remove_meta_box($box, $types[$box], 'normal');
            }
        });
        
        // Sanitize file names on save.
        add_filter('sanitize_file_name', function ($name) {
            $path = pathinfo($name);
        
            if (!isset($path['extension'])) {
                return $name;
            }
        
            $filename = preg_replace(sprintf('/.%s$/', $path['extension']), '', $name);
        
            return sprintf('%s.%s', sanitize_title($filename), $path['extension']);
        }, 10, 2);
        
    }

    /**
     * Remove menu and submenu items from the admin bar when logged in.
     * @return null
     */
    public function removeAdminBarItems()
    {
        add_action('admin_bar_menu', function ($menu) {
            $items = get_theme_support('plate-disable-toolbar')[0];
        
            foreach ($items as $item) {
                $menu->remove_node($item);
            }
        }, 999);
    }

    /**
     * Set custom footer text
     * @return null
     */
    public function setCustomFooterText()
    {
        add_filter('admin_footer_text', function () {
            return get_theme_support('plate-footer-text')[0];
        });
    }

    /**
     * Set custom Admin Logo
     * @return null
     */
    public function setCustomLogo()
    {
        // Set custom login logo.
        add_action('login_head', function () {
            $args = get_theme_support('plate-login-logo');

            if (empty($args[0])) {
                return;
            }

            $styles = [
                'background-position: center;',
                'background-size: contain;',
                sprintf('background-image: url(%s);', $args[0]),
            ];

            if (count($args) >= 2) {
                $styles[] = sprintf('width: %dpx;', $args[1]);
            }

            echo sprintf('<style> .login h1 a { %s } </style>', implode(' ', $styles));
        });

        // Set custom login logo url.
        add_filter('login_headerurl', function () {
            return get_bloginfo('url');
        });

        // Set custom login error message.
        add_filter('login_errors', function () {
            return '<strong>Whoops!</strong> Looks like you missed something there. Have another go.';
        });
    }
}