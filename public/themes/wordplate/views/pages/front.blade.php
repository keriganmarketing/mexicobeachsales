@extends('layouts.main')

@section('content')
@if (have_posts())
    @while (have_posts())
        {{ the_post() }}
                
        @if(get_theme_mod('header_feature') == 'slider')
            @include('partials.slider')
        @endif

        @if(get_theme_mod('header_feature') == 'main-image')
            @include('partials.headerimage')
        @endif

        @if(get_theme_mod('header_feature') == 'background-video')
            @include('partials.video')
        @endif
        <a class="down-arrow" v-scroll-to="'#main'" ><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
        <main role="main" id="main" class="py-5">
            <div class="container">
                <div class="row justify-content-center ">
                    @if($headshot != '')
                        <div class="col-6 col-sm-4 col-lg-3 mb-5 mb-sm-0" >
                            <img src="{{ $headshot }}" class="img-fluid" alt="{{ get_field('agent_name','option') }}">
                        </div>
                    @endif
                    <div class="col-sm-8 col-lg-9">
                        <article class="front text-center text-sm-left">
                            <h1>{{ the_title() }}</h1>
                            
                            {{ the_content() }}

                        </article>
                    </div>
                </div>
            </div>
        </main>

    @endwhile
@else
    @include('pages.404')
@endif
@endsection