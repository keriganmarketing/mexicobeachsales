<?php

$search = new KeriganSolutions\KMARealtor\Search();

$search->setRequest([
    'sort' => 'list_date|desc',
    'status' => [
        'sold' => 'Sold/Closed',
    ]
]);

bladerunner('views.pages.customsearch', [
    'results'        => $search->getListings(),
    'currentRequest' => $search->getCurrentRequest(),
    'resultsMeta'    => $search->getResultMeta(),
    'currentSort'    => $search->getSort(),
    'pagination'     => $search->buildPagination(),
    'headerImage'    => (get_field('header_image') ? (get_field('header_image'))['url'] : wp_get_attachment_url(get_theme_mod('home_header_image'))),
    'headerOverlay'  => get_field('overlay_color'),
    'headline'       => $search->enhanceTitle()
]);
