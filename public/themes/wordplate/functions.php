<?php

declare(strict_types=1);

require template_path('includes/ThemeControl.php');
require template_path('includes/plugins/plate.php');
require template_path('post-types/contact_request.php');
$wordplate = new ThemeControl();

// Set theme defaults.
add_action('after_setup_theme', function () {
    // Disable the admin toolbar.
    show_admin_bar(false);

    add_theme_support('post-thumbnails');
    add_theme_support( 'custom-logo', [
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => ['site-title', 'site-description'],
    ] );
    add_theme_support('title-tag');
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'widgets',
    ]);
    add_theme_support( 'post-formats', [
        // 'aside',
        'gallery',
        'image',
        'status',
        'quote', 
        'video'
    ]);
});

// Enqueue and register scripts the right way.
add_action('wp_enqueue_scripts', function () {
    wp_deregister_script('jquery');
    wp_enqueue_style('wordplate', mix('styles/main.css'), [], null);
    wp_register_script('wordplate', mix('scripts/app.js'), '', '', true);
    wp_enqueue_script('wordplate', mix('scripts/app.js'), '', '', true);
});

// Custom Blade Cache Path
add_filter('bladerunner/cache/path', function () {
    return '../../uploads/.cache';
});


//[quicksearch]
function quicksearch_func( $atts ){
    ob_start();
    ?>
    <quick-search search-terms=""></quick-search>

    <div class="d-flex feat-buttons">
        <a class="btn top-homes btn-opaque flex-grow-1 font-weight-black" href="/top-25-residential-buys/" ><span class="font-weight-light">Top 25</span><br>Homes</a>
        <a class="btn top-lots btn-opaque flex-grow-1 font-weight-black" href="/top-15-lots/" ><span class="font-weight-light">Top 15</span><br>Lots</a>
        <a class="btn storm-deals flex-grow-1 font-weight-black" href="https://www.98realestategroup.com/properties/michael/" target="_blank"><span class="font-weight-light">Storm</span><br>Deals</a>
    </div>
    <?php
	return ob_get_clean();
}
add_shortcode( 'quicksearch', 'quicksearch_func' );

function getVideoImageFromEmbed($postContent){
    if($postContent == ''){
        return false;
    }
    preg_match('/src="(.*?)"/', $postContent, $video);

    print_r($video);
    $videoParts = explode('/',$video[2]);
    return 'https://img.youtube.com/vi/'.$videoParts[3].'/maxresdefault.jpg';
}

add_filter('wpseo_opengraph_image', function () {
    if ( get_post_format() == 'video' ) {
        $post = get_post();
        return getVideoImageFromEmbed($post->post_content);
    }
});

//[quicksearch]
function testimonial_func( $atts ){
    $testimonials = (new KeriganSolutions\KMATestimonials\Testimonial)->queryTestimonials(false, -1, 'date', 'ASC', 100);

    ob_start();
    ?>
    <div class="testimonials">
        <?php foreach($testimonials as $testimonial){ ?>
            <div class="testimonial border-bottom py-3" >
                <a class="pad-anchor" name="review-<?php echo $testimonial->ID; ?>"></a>
                <p><?php echo wp_trim_words($testimonial->post_content, 100, '... <a href="'.get_permalink($testimonial->ID).'">read more.</a>'); ?></p>
                <p class="byline">&mdash; <?php echo $testimonial->byline; ?></p>
            </div>
        <?php } ?>
    </div>
    <?php
	return ob_get_clean();
}
add_shortcode( 'testimonials', 'testimonial_func' );