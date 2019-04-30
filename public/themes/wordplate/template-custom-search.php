<?php
 /* Template Name: MLS Results */

 $search = new KeriganSolutions\KMARealtor\Search();

 $search->setRequest([
    'omni' => get_field('omni'),
    'area' => get_field('area'),
    'propertyType' => get_field('property_type'),
    'forclosure' => (get_field('foreclosure') ? 'on' : null),
    'minPrice' => get_field('min_price'),
    'maxPrice' => get_field('max_price'),
    'beds' => get_field('beds'),
    'baths' => get_field('baths'),
    'sqft' => get_field('sqft'),
    'acreage' => get_field('acreage'),
    'waterfront' => (get_field('waterfront') ? 'on' : null),
    'status' => get_field('status'),
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
