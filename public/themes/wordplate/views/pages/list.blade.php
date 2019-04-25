@extends('layouts.main')
@section('content')

@if (have_posts())
    @while (have_posts())
        {{ the_post() }}
        @include('partials.mast')
        <main role="main" class="pb-5">
            <div class="container">
                <article class="support">
                    <header class="pt-0 pt-xl-5 text-center text-md-left">
                        <h1>{{ the_title() }}</h1>
                    </header>
                    {{ the_content() }}
                </article>

                @if(count($listings) > 0)

                    @foreach($listings as $listing)
                    <hr>
                    <h2 class="display-3 py-4">#{{ $listing->menu_order }}: {{ $listing->post_title }}</h2>

                    <div class="row mb-5">
                        <div class="col-md-4">
                            <img src="{{ $listing->media_objects->data[0]->url }}" class="img-fluid shadow" >
                            <photo-gallery 
                            :mls-account="{{ $listing->mls_account }}"
                            @closeviewer="closeGallery"
                            @openviewer="openGallery"
                            class="d-none d-sm-block"
                            :viewer-state="galleryIsOpen"
                            virtual-tour='{{ $listing->virtual_tour }}' 
                            :data-photos='{{ json_encode($listing->media_objects->data) }}'
                            item-class="col-sm-6"
                            :limit="4" ></photo-gallery>
                        </div>
                        <div class="col-md-8">

                            <div class="pt-4 row">
                                <div class="col-12 col-lg-auto">
                                    <a class="btn btn-block d-sm-inline-block btn-primary mb-4" href="/listing/{{ $listing->mls_account }}" >View Listing Details</a> 
                                </div>
                                @if($listing->virtual_tour != '')
                                <div class="col-6 col-lg-auto">
                                    <a class="btn btn-block btn-secondary mb-4" target="_blank" href="//{{ $listing->virtual_tour }}" >Virtual Tour</a> 
                                </div>
                                @endif
                                @if(count($listing->media_objects->data ) > 1)
                                <div class="col-6 col-lg-auto">
                                    <a @click="openGallery([{{ $listing->mls_account }}])" class="btn btn-block btn-secondary mb-4 pointer text-white" >Photos ({{ count($listing->media_objects->data ) }})</a>
                                </div>
                                @endif
                            </div>

                            <div class="card">
                                <table class="table m-0">
                                <tr><td>MLS Number</td><td>{{ $listing->mls_account }}</td></tr>
                                @if($listing->list_date != '')
                                    <tr><td>List Date</td><td>{{ date('M d, Y', strtotime($listing->list_date)) }}</td></tr>
                                @endif
                                @if($listing->bedrooms != '' && $listing->bedrooms != '0')
                                    <tr><td>Bedrooms</td><td>{{ number_format($listing->bedrooms) }}</td></tr>
                                @endif
                                @if($listing->full_baths != '' && $listing->full_baths != '0')    
                                    <tr><td>Full Bathrooms</td><td>{{ number_format($listing->full_baths) }}</td></tr>
                                @endif
                                @if($listing->half_baths != '' && $listing->half_baths != '0')    
                                    <tr><td>Half Bathrooms</td><td>{{ number_format($listing->half_baths) }}</td></tr>
                                @endif
                                @if($listing->acreage != '' && $listing->acreage != '0')
                                    <tr><td>Acreage</td><td>{{ $listing->acreage }} Acres</td></tr>
                                @endif
                                @if($listing->total_hc_sqft != '' && $listing->total_hc_sqft != '0')
                                    <tr><td>H/C SqFt</td><td>{{ number_format($listing->total_hc_sqft) }} SqFt</td></tr>
                                @endif
                                @if($listing->sqft != '' && $listing->sqft != '0')
                                    <tr><td>Total SqFt</td><td>{{ number_format($listing->sqft) }} SqFt</td></tr>
                                @endif
                                @if($listing->lot_dimensions != '' && ($listing->lot_dimensions != '0' || $listing->lot_dimensions != ''))
                                    <tr><td>Lot Size</td><td>{{ $listing->lot_dimensions }}</td></tr>
                                @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    @endforeach

                    @include('partials.disclaimer')

                @endif

            </div>

        </main>
    @endwhile
@else
    @include('pages.404')
@endif
@endsection

@section('modals')
    @foreach($listings as $listing)
        <portal-target name="modal-{{ $listing->mls_account }}"></portal-target>
    @endforeach
@endsection