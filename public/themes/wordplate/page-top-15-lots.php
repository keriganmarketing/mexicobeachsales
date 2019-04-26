<?php
bladerunner('views.pages.list', [
    'listings' => (new KeriganSolutions\KMARealtor\FeaturedLists())->getListings('top-lot',15),
    'headerImage' => (get_field('header_image') ? (get_field('header_image'))['url'] : wp_get_attachment_url(get_theme_mod('home_header_image'))),
    'headerOverlay' => get_field('overlay_color'),
]);