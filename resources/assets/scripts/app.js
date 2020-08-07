import Vue from 'vue'

import {VueMasonryPlugin} from 'vue-masonry';
Vue.use(VueMasonryPlugin)

import PortalVue from 'portal-vue';
Vue.use(PortalVue);

import VueCarousel from 'vue-carousel';
Vue.use(VueCarousel);

import VueLazyload from 'vue-lazyload';
Vue.use(VueLazyload, {
    lazyComponent: true,
    preLoad: 1.1,
    error: '/themes/wordplate/assets/images/loading.svg',
    loading: '/themes/wordplate/assets/images/loading.svg',
    attempt: 1,
    observer: true,
    observerOptions: {
      rootMargin: '0px',
      threshold: 0.1
    }
})

import vueScrollto from 'vue-scrollto'
Vue.use(vueScrollto, {
    container: "body",
    duration: 1000,
    easing: "ease",
    offset: -120,
    force: true,
    cancelable: true,
    onStart: false,
    onDone: false,
    onCancel: false,
    x: false,
    y: true
})

//navigation menus
Vue.component('main-menu', require('./components/navigation/MainNavigationMenu.vue').default);
Vue.component('mobile-menu', require('./components/navigation/MobileNavigationMenu.vue').default);

//plugins
Vue.component('social-icons', require('./components/SocialMediaIcons.vue').default);
Vue.component('kma-slider', require('./components/KMASliderModule.vue').default);
Vue.component('contact-form', require('./components/ContactForm.vue').default);
Vue.component('fit-text', require('./components/FitText.vue').default);
Vue.component('photo-gallery', require('./components/PhotoGallery.vue').default);
Vue.component('listing-photo', require('./components/ListingPhoto.vue').default);
Vue.component('featured-list', require('./components/FeaturedList.vue').default);
Vue.component('listing-large', require('./components/ListingLarge.vue').default);
Vue.component('social-sharing-icons', require('./components/SocialSharingIcons.vue').default); 

//search
Vue.component('omni-bar', require('./components/fields/OmniBar.vue').default);
Vue.component('acreage-field', require('./components/fields/Acreage.vue').default);
Vue.component('sqft-field', require('./components/fields/TotalSqft.vue').default);
Vue.component('bathrooms-field', require('./components/fields/Bathrooms.vue').default);
Vue.component('bedrooms-field', require('./components/fields/Bedrooms.vue').default);
Vue.component('max-price-field', require('./components/fields/MaxPrice.vue').default);
Vue.component('min-price-field', require('./components/fields/MinPrice.vue').default);
Vue.component('status-field', require('./components/fields/Status.vue').default);
Vue.component('property-type', require('./components/fields/PropertyType.vue').default);
Vue.component('area-field', require('./components/fields/Area.vue').default);
Vue.component('details-field', require('./components/fields/Details.vue').default);
Vue.component('search-bar', require('./components/SearchBar.vue').default);
Vue.component('sort-form', require('./components/SortForm.vue').default);
Vue.component('video-bg', require('./components/VideoBackground.vue').default);
Vue.component('quick-search', require('./components/QuickSearch.vue').default);

const app = new Vue({
    el: '#app',

    data: {
        clientHeight: 0,
        windowHeight: 0,
        windowWidth: 0,
        isScrolling: false,
        scrollPosition: 0,
        footerStuck: false,
        mobileMenuOpen: false,
        galleryIsOpen: ''
    },

    methods: {
        handleScroll () {
            this.scrollPosition = window.scrollY;
            this.isScrolling = this.scrollPosition > 40;
        },
        toggleMenu() { 
            this.mobileMenuOpen = ! this.mobileMenuOpen;
        },
        openGallery(payload) {
            this.galleryIsOpen = payload[0];
        },
        closeGallery() {
            this.galleryIsOpen = '';
        }
    },

    mounted () {
        this.footerStuck = window.innerHeight > this.$root.$el.children[0].clientHeight;
        this.clientHeight = this.$root.$el.children[0].clientHeight;
        this.windowHeight = window.innerHeight;
        this.windowWidth = window.innerWidth;
        this.handleScroll();
    },

    created () {
        window.addEventListener('scroll', this.handleScroll, {passive: true});
    },

    destroyed () {
        window.removeEventListener('scroll');
    }

})
