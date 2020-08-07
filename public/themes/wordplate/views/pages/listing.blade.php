@extends('layouts.main')
@section('content')

@if (have_posts())
    @while (have_posts())
        {{ the_post() }}
        @include('partials.mast')
        <main role="main" class="pb-5">
            <div class="container">
                <article class="support">

                    <div class="row align-items-center">

                        <div class="col-12 col-lg-auto pl-xl-5 order-lg-2">
                            <div class="d-flex flex-lg-column flex-wrap align-items-center align-lg-items-start">
                                <div class="col-md-6 col-lg-auto">
                                @include('listing.header')
                                </div>
                                <div class="col-md-6 col-lg-auto text-center text-md-left">
                                @include('listing.actionbuttons')
                                </div>   
                            </div>
                        </div>

                        <div class="col-12 col-lg order-lg-1 flex-grow-1">
                            @include('listing.mainphoto')
                        </div>

                    </div>
                    <div class="row">
                        
                        <div class="col-12 col-lg pt-lg-2">
                            <p>{{ $listing->remarks }}</p>
                        </div>

                        <div class="col-12 col-lg-auto pb-4">
                            <div class="p-4 d-flex flex-column align-items-center">
                                <h3 class="text-muted">Share this property</h3>
                                <div class="d-flex flex-wrap" >
                                <social-sharing-icons
                                    network="email"
                                    icon="envelope"
                                    class="share-network email"
                                    url="{{ (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" }}"
                                    title="{{ the_title() . ' | ' . $listing->city . ', ' . $listing->state }}"
                                    description="{{ $listing->remarks }}"
                                ></social-sharing-icons>
                                <social-sharing-icons
                                    network="facebook"
                                    icon="facebook"
                                    class="share-network facebook"
                                    url="{{ (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" }}"
                                    title="{{ the_title() . ' | ' . $listing->city . ', ' . $listing->state }}"
                                    description="{{ wp_trim_words($listing->remarks, 20, '...') }}"
                                ></social-sharing-icons>
                                <social-sharing-icons
                                    network="linkedin"
                                    icon="linkedin"
                                    class="share-network linkedin"
                                    url="{{ (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" }}"
                                    title="{{ the_title() . ' | ' . $listing->city . ', ' . $listing->state }}"
                                ></social-sharing-icons>
                                <social-sharing-icons
                                    network="pinterest"
                                    icon="pinterest"
                                    class="share-network pinterest"
                                    url="{{ (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" }}"
                                    title="{{ the_title() . ' | ' . $listing->city . ', ' . $listing->state }}"
                                    media="{{ $listing->media_objects->data[0]->url }}"
                                ></social-sharing-icons>
                                <social-sharing-icons
                                    network="twitter"
                                    icon="twitter"
                                    class="share-network twitter"
                                    url="{{ (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" }}"
                                    title="{{ the_title() . ' | ' . $listing->city . ', ' . $listing->state }}"
                                ></social-sharing-icons>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <photo-gallery 
                        :mls-account="{{ $listing->mls_account }}"
                        @closeviewer="closeGallery"
                        @openviewer="openGallery"
                        :viewer-state="galleryIsOpen"
                        class="d-none d-sm-block mt-4 mb-5"
                        virtual-tour='{{ $listing->virtual_tour }}' 
                        :data-photos='{{ json_encode($listing->media_objects->data) }}'
                        item-class="col-sm-6 col-md-4 col-lg-3 col-xl-2" ></photo-gallery>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            @include('listing.details')
                        </div>
                        <div class="col-md-6 mb-4">
                            @include('listing.construction')
                        </div>

                        <div class="col-md-5 mb-4">
                            @include('listing.location')
                        </div>
                        <div class="col mb-4">
						    @include('listing.map')
                        </div>
                    </div>

                    @if($listing->listing_office_name != '')
                    <p class="text-center">This listing is managed by {{ $listing->listing_office_name }}.</p>
                    @endif
                    
                    @include('partials.disclaimer')
                    
                </article>
            </div>
        </main>
    @endwhile
@else
    @include('pages.404')
@endif
@endsection

@section('modals')
    <portal-target v-if="galleryIsOpen !== ''" name="gallery-modal"></portal-target>
@endsection