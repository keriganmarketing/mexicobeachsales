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

                    @foreach($listings as $miniListing)
                    <hr>
                    <h2 class="display-3 pt-4">
                    #{{ $miniListing->menu_order }}: {{ $miniListing->post_title }} - {{ $miniListing->sub_area }}</h2>
                    @if(date('Ymd', strtotime($miniListing->list_date)) >= date('Ymd', strtotime('-10 days')))
                        <h3 style="font-size:16px;" class="badge badge-info d-inline-block mr-2">Just Listed</h3>
                    @endif
                    @if($miniListing->status == 'Sold/Closed')
                        <h3 style="font-size:16px;" class="badge badge-info d-inline-block mr-2">Sold on <?php echo date( 'M j, Y', strtotime( $miniListing->sold_on ) ); ?> 
                                    for $<?php echo number_format( $miniListing->sold_for ); ?></h3>
                    @endif
                    @if($miniListing->status == 'Contingent')
                        <h3 style="font-size:16px;" class="badge badge-info d-inline-block mr-2">SALE CONTINGENT</h3>
                    @endif
                    @if($miniListing->original_list_price > $miniListing->price && $miniListing->status == 'Active' && $miniListing->original_list_price != 0)
                        <h3 style="font-size:16px;" class="badge badge-danger d-inline-block mr-2">REDUCED <span style="text-decoration:line-through">$<?php echo number_format( $miniListing->original_list_price ); ?></span> <strong>$<?php echo number_format( $miniListing->price); ?></strong></h3>
                    @endif

                    <h3 style="font-size:16px;" class="badge badge-secondary d-inline-block mr-2">MLS# {{ $miniListing->mls_account }}</h3>

                    <div class="row mb-5 pt-4">
                        <div class="col-md-4">
                            <img src="{{ $miniListing->media_objects->data[0]->url }}" class="img-fluid shadow" >
                            <photo-gallery 
                            :mls-account="{{ $miniListing->mls_account }}"
                            @closeviewer="closeGallery"
                            @openviewer="openGallery"
                            class="d-none d-sm-block"
                            :viewer-state="galleryIsOpen"
                            virtual-tour='{{ $miniListing->virtual_tour }}' 
                            :data-photos='{{ json_encode($miniListing->media_objects->data) }}'
                            item-class="col-sm-6"
                            :limit="5" ></photo-gallery>
                        </div>
                        <div class="col-md-8">

                            <div class="pt-4 row">
                                <div class="col-12 col-lg-auto">
                                    <a class="btn btn-block d-sm-inline-block btn-primary mb-4" href="/listing/{{ $miniListing->mls_account }}" >View Listing Details</a> 
                                </div>
                                @if($miniListing->virtual_tour != '')
                                <div class="col-6 col-lg-auto">
                                    <a class="btn btn-block btn-secondary mb-4" target="_blank" href="//{{ $miniListing->virtual_tour }}" >Virtual Tour</a> 
                                </div>
                                @endif
                                @if(count($miniListing->media_objects->data ) > 1)
                                <div class="col-6 col-lg-auto">
                                    <a @click="openGallery([{{ $miniListing->mls_account }}])" class="btn btn-block btn-secondary mb-4 pointer text-white" >Photos ({{ count($miniListing->media_objects->data ) }})</a>
                                </div>
                                @endif
                            </div>

                            <div class="card">
                                <table class="table table-striped m-0">
                                <tr><td>Price</td><td>${{ number_format($miniListing->price) }}</td></tr>
                                
                                @if($miniListing->full_address != '')
                                    <tr><td>Address</td><td>{{ $miniListing->full_address }}</td></tr>
                                @endif
                                @if($miniListing->bedrooms != '' && $miniListing->bedrooms != '0')
                                    <tr><td>Bedrooms</td><td>{{ number_format($miniListing->bedrooms) }}</td></tr>
                                @endif
                                @if($miniListing->full_baths != '' && $miniListing->full_baths != '0')    
                                    <tr><td>Full Bathrooms</td><td>{{ number_format($miniListing->full_baths) }}</td></tr>
                                @endif
                                @if($miniListing->half_baths != '' && $miniListing->half_baths != '0')    
                                    <tr><td>Half Bathrooms</td><td>{{ number_format($miniListing->half_baths) }}</td></tr>
                                @endif
                                @if($miniListing->acreage != '' && $miniListing->acreage != '0')
                                    <tr><td>Acreage</td><td>{{ $miniListing->acreage }} Acres</td></tr>
                                @endif
                                @if($miniListing->total_hc_sqft != '' && $miniListing->total_hc_sqft != '0')
                                    <tr><td>H/C SqFt</td><td>{{ number_format($miniListing->total_hc_sqft) }} SqFt</td></tr>
                                @endif
                                @if($miniListing->sqft != '' && $miniListing->sqft != '0')
                                    <tr><td>Total SqFt</td><td>{{ number_format($miniListing->sqft) }} SqFt</td></tr>
                                @endif
                                @if($miniListing->lot_dimensions != '' && ($miniListing->lot_dimensions != '0' || $miniListing->lot_dimensions != ''))
                                    <tr><td>Lot Size</td><td>{{ $miniListing->lot_dimensions }}</td></tr>
                                @endif
                                @if($miniListing->list_date != '')
                                    <tr><td>List Date</td><td>{{ date('M d, Y', strtotime($miniListing->list_date)) }}</td></tr>
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
    @foreach($listings as $miniListing)
        <portal-target name="modal-{{ $miniListing->mls_account }}"></portal-target>
    @endforeach
@endsection