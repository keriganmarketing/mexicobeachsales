<?php

namespace KMA\Modules\KMAHelpers;

class RestMenus {
    public function use()
    {
        $this->createEndpoint();
    }

    private function createEndpoint()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'kerigansolutions/v1', '/navigation-menu', array(
                'methods' => 'GET',
                'callback' => [$this, 'getMenuJson']
            ) );
        } );
    }

    public function getWebsiteMenu( $menuID ){
        $data = wp_get_nav_menu_items($menuID, [
            'orderby' => 'menu_order',
            'order'   => 'ASC'
        ]);
        $tempArray = [];
        $output = [];
    
        if(!is_array($data)){
            return '';
        }
    
        foreach($data as $key => $item){
            if($item->menu_item_parent == 0){
                $item->children = [];
                $tempArray[$item->ID] = $item;
            }else{
                $tempArray[$item->menu_item_parent]->children[] = $item;
            }
        }
    
        foreach($tempArray as $key => $item){
            $item->title = htmlspecialchars_decode($item->title);
            $item->classes = implode(' ', $item->classes);
            $output[$item->menu_order] = $item;
        }
    
        return $output;
    }
    
    public function getMenuJson( $request ) {
        $menu_items = array();
    
        if ( ($locations = get_nav_menu_locations()) && isset($locations[$request->get_param( 'slug' )]) && $locations[$request->get_param( 'slug' )] != 0 ) {
            $menu = get_term( $locations[ $request->get_param( 'slug' ) ] );
            $menu_items = $this->getWebsiteMenu($menu->term_id);
        }
        
        return rest_ensure_response( $menu_items );
    }
}