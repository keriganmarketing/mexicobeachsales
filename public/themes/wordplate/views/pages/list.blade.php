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

                <featured-list type="{{ $item }}" :limit="{{ $limit }}"></featured-list>

               

                    @include('partials.disclaimer')

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