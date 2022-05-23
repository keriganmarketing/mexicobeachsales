<?php
namespace KMA\Modules\KMAServices;

use KMA\Modules\KMAHelpers\CustomTaxonomy;
use KMA\Modules\KMAHelpers\CustomPostType;

class Reviews {

    public function __construct()
    {
        //blank on purpose
    }

    public function use()
    {

        new CustomPostType('review', 'Reviews', 'Review', 'Reviews', 'format-quote');
        //register_taxonomy( 'service-group', 'review' );

        if ( function_exists( 'acf_add_local_field_group' ) ) {
            add_action( 'init', [$this, 'registerFields'] );
        }

        // $this->setupEnpoints();
        $this->setupShortcodes();
    }

    protected function setupShortcodes()
    {
        add_shortcode( 'testimonials', [$this, 'testimonialsShortcode'] );
    }

    protected function setupEnpoints()
    {
        add_action('rest_api_init', [$this, 'addRoutes']);
    }

    /**
     * Add REST API routes
     */
    //public function addRoutes()
    //{
    //     register_rest_route(
    //        'kerigansolutions/v1',
    //         '/reviews',
    //         [
    //             'methods' => 'GET',
    //            'callback' => [$this, 'getReviews'],
    //            'permission_callback' => '__return_true'
    //         ]
    //     );
    //}

    public function registerFields()
    {
        acf_add_local_field_group(array(
            'key' => 'group_5ed648acce141',
            'title' => 'Review Details',
            'fields' => array(
                array(
                    'key' => 'field_5ed648b24cfa3',
                    'label' => 'Author',
                    'name' => 'author',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
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
                    'key' => 'field_5ed648c74cfa4',
                    'label' => 'Location',
                    'name' => 'location',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
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
                    'key' => 'field_5ed648cf4cfa5',
                    'label' => 'Date',
                    'name' => 'date',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'review',
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
    }

    /**
     * Querys the database for custom post types
     * @param Number limit
     * @param String category
     * @return Array
     */
    public function query(  $category = '', $limit = -1, $orderby = 'menu_order', $order = 'DESC' )
    {
        $request = [
            'posts_per_page' => $limit,
            'offset'         => 0,
            'order'          => $order,
            'orderby'        => $orderby,
            'post_type'      => 'review',
            'post_status'    => 'publish',
        ];

        /*if ($category != '') {
            $categoryarray = [
                [
                    'taxonomy'         => 'service-group',
                    'field'            => 'slug',
                    'terms'            => $category,
                    'include_children' => false,
                ],
            ];
            $request['tax_query'] = $categoryarray;
        }*/

        $posts = get_posts($request);
        $output = [];
        foreach($posts as $post){
            $output[] = $this->addCustomFields($post);
        }

        return $output;
    }

    /**
     * Gets a random review from WordPress
     * @param String category optional
     */
    public function getRand( $category = '' )
    {
        $review = $this->query(1, $category, 'rand');
        if($review){
            return $review[0];
        }else{
            return [];
        }
    }

    /**
     * Gets all the custom fields for the post and adds them to the post object
     * @param Object post
     * @return Object post
     */
    public function addCustomFields($post)
    {
        //$category = (get_the_terms( $post->ID, 'service-group' ))[0];
        //if(isset($category)){
        //    $post->category = $category->slug;
        //}

        $post->author   = get_field('author', $post->ID);
        //$post->post_content = get_field('content', $post->ID);
        //$post->location = get_field('location', $post->ID);
        //$post->date     = get_field('date', $post->ID);

        return $post;
    }

    public function testimonialsShortcode( $atts )
    {
        $atts = shortcode_atts(
            array(
                'limit' => -1,
                'category' => null,
                'orderby'  => 'menu_order',
                'order'    => 'ASC',
                'truncate' => 0,
                'item_class' => 'col-md-6 col-lg-4 col-xl-3 mb-4',
            ), $atts, 'testimonials' );

        $testimonials = $this->query( $atts['category'], $atts['limit'], $atts['orderby'], $atts['order'] );

        ob_start();
        //echo '<pre>', print_r($testimonials), '</pre>';
        ?>
        <section class="section testimonials pb-5" ref="testimonials">
            <div class="card-columns">
            <?php foreach($testimonials as $testimonial){ ?>
                <div class="card my-3 pb-3 border-bottom">
                    <div class="blockquote mb-3 card-body">
                        <div class="open-quote text-accent"></div>
                        <?php echo $testimonial->post_content; ?>
                        <footer class="blockquote-footer d-flex justify-content-end align-items-center">
                            <small class="text-muted">
                                <?php echo ($testimonial->author != '' ?  $testimonial->author : ''); ?>
                            </small>
                        </footer>
                    </div>
                </div>
            <?php } ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

}
