<?php 
namespace KMA\Modules\KMATeam;

use KMA\Modules\KMAHelpers\CustomTaxonomy;
use KMA\Modules\KMAHelpers\CustomPostType;

class Team {

    public $postType;
    public $menuName;
    public $menuIcon;
    public $singularName;
    public $pluralName;
    public $showSocial;
    public $dashicon;

    public function __construct()
    {
        //blank on purpose
    }
    
    public function use()
    {
        $this->postType = 'team';
        $this->menuName = 'Team';
        $this->singularName = 'Member';
        $this->pluralName = 'Team';
        $this->menuIcon = 'id';
        $this->showSocial = false;
        $this->dashicon = 'businessperson';

        $this->createPosttype();
        $this->createTaxonomy();
        $this->setupEnpoints();
        $this->setupShortcodes();
    }

    protected function setupShortcodes()
    {
        add_shortcode( 'team', [$this, 'teamShortcode'] );
    }

    protected function setupEnpoints()
    {
        add_action('rest_api_init', [$this, 'addRoutes']);
    }

    public function createTaxonomy()
    {
        add_action( 'init', [$this, 'createTaxonomy'] );
        add_filter( 'term_updated_messages', [$this, 'taxonomyUpdated'] );
    }

    public function createPosttype()
    {
        new CustomPostType($this->postType, $this->pluralName, $this->singularName, $this->pluralName, $this->dashicon);
        new CustomTaxonomy($this->postType . '-group', $this->singularName . ' Group', $this->singularName . ' Groups', [$this->postType], true);

        if (function_exists('acf_add_local_field_group')) {
            add_action('acf/init', [$this, 'registerFields']);
        }
    }

    public function teamShortcode( $atts )
    {
        $atts = shortcode_atts(
            array(
                'limit' => -1,
                'department' => null,
                'item_class' => 'col-sm-6 col-lg-4 col-xl-3 py-3 mb-4 text-center d-flex flex-column align-items-center',
            ), $atts, 'team' );
            
        $team = $this->query( $atts['limit'], $atts['department'] );
        
        ob_start();
        ?>
        <div class="team row">
            <?php foreach($team as $member){ ?>
                <div class="<?php echo $atts['item_class']; ?>" >
                    <div class="w-auto">
                        <img v-lazy="'<?php echo $member->image['sizes']['thumbnail']; ?>'" class="mx-auto mbn-2 rounded-circle border border-secondary" >
                    </div>
                    <div class="team-content flex-grow my-2">
                        <h3 class="pt-3 mb-0 display-4 text-primary"><?php echo $member->post_title; ?></h3>
                        <p class="m-0 text-secondary text-uppercase"><?php echo $member->title; ?></p>
                        <?php if($member->phone != '') { ?>
                        <p class="m-0"><a class="text-primary" href="tel:<?php echo $member->phone; ?>" ><?php echo $member->phone; ?></a></p>
                        <?php } ?>
                        <?php if($member->email != '') { ?>
                        <p class="m-0"><a class="text-primary" href="mailto:<?php echo $member->email; ?>" ><?php echo $member->email; ?></a></p>
                        <?php } ?>
                    </div>
                    <?php if( $member->post_content != ''){ ?>
                        <a class="btn btn-sm btn-secondary" href="<?php echo $member->link; ?>">read bio</a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Querys the database for custom post types
     * @param Number limit
     * @param String category
     * @return Array
     */
    public function query( $limit = -1, $category = '' )
    {
        $request = [
            'posts_per_page' => $limit,
            'offset'         => 0,
            'order'          => 'ASC',
            'orderby'        => 'menu_order',
            'post_type'      => $this->postType,
            'post_status'    => 'publish',
        ];

        if ($category != '') {
            $categoryarray = [
                [
                    'taxonomy'         => $this->postType . '-group',
                    'field'            => 'slug',
                    'terms'            => $category,
                    'include_children' => false,
                ],
            ];
            $request['tax_query'] = $categoryarray;
        }

        $posts = get_posts($request);
        $output = [];
        foreach($posts as $post){
            $output[] = $this->addCustomFields($post);
        }

        return $output;
    }

    /**
     * Gets all the custom fields for the post and adds them to the post object
     * @param Object post
     * @return Object post
     */
    public function addCustomFields($post)
    {
        $post->link = get_permalink($post->ID);

        $post->title = get_field('title', $post->ID);
        $post->image = get_field('image', $post->ID);
        $post->email = get_field('email', $post->ID);
        $post->phone = get_field('phone', $post->ID);
        $post->facebook = get_field('facebook', $post->ID);
        $post->linkedin = get_field('linkedin', $post->ID);
        $post->instagram = get_field('instagram', $post->ID);
        $post->twitter = get_field('twitter', $post->ID);

        $category = (get_the_terms( $post->ID, 'service-group' ))[0];
        if(isset($category)){
            $post->category = $category->slug;
        }
        return $post;
    }

    /*
     * Get slides using REST API endpoint
     */
    public function getTeam($request)
    {
        $limit = $request->get_param('limit');
        $department = $request->get_param('department');
        return rest_ensure_response($this->query($limit, $department));
    }

    /**
     * Add REST API routes
     */
    public function addRoutes()
    {
        register_rest_route(
            'kerigansolutions/v1',
            '/team',
            [
                'methods' => 'GET',
                'callback' => [$this, 'getTeam']
            ]
        );
    }

    public function registerFields()
    {
        // ACF Group: slide Details
        acf_add_local_field_group(array(
            'key' => 'group_team_details',
            'title' => $this->singularName . ' info',
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'team',
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

        // title
        acf_add_local_field(array(
            'key' => 'title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'parent' => 'group_team_details',
            'instructions' => 'Example: VP, Customer Relations',
        ));
        // email
        acf_add_local_field(array(
            'key' => 'email',
            'label' => 'Email',
            'name' => 'email',
            'type' => 'text',
            'parent' => 'group_team_details',
        ));
        // phone
        acf_add_local_field(array(
            'key' => 'phone',
            'label' => 'Phone',
            'name' => 'phone',
            'type' => 'text',
            'parent' => 'group_team_details',
        ));
        // Image
        acf_add_local_field(array(
            'key' => 'image',
            'label' => 'Image',
            'name' => 'image',
            'type' => 'image',
            'parent' => 'group_team_details',
            'instructions' => '',
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'min_width' => 0,
            'min_height' => 0,
            'max_width' => 0,
            'max_height' => 0,
        ));

        if ($this->showSocial) {
            // facebook
            acf_add_local_field(array(
                'key' => 'facebook',
                'label' => 'Facebook URL',
                'name' => 'facebook',
                'type' => 'text',
                'parent' => 'group_team_details',
            ));
            // linkedin
            acf_add_local_field(array(
                'key' => 'linkedin',
                'label' => 'LinkedIn URL',
                'name' => 'linkedin',
                'type' => 'text',
                'parent' => 'group_team_details',
            ));
            // instagram
            acf_add_local_field(array(
                'key' => 'instagram',
                'label' => 'Instagram URL',
                'name' => 'instagram',
                'type' => 'text',
                'parent' => 'group_team_details',
            ));
            // twitter
            acf_add_local_field(array(
                'key' => 'twitter',
                'label' => 'Twitter URL',
                'name' => 'twitter',
                'type' => 'text',
                'parent' => 'group_team_details',
            ));
        }

    }
}