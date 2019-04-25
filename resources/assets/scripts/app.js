if (!window._babelPolyfill) {
    window._babelPolyfill = require('babel-polyfill');
}

window.Vue = require('vue');
window.axios = require('axios');
window._ = require("lodash");

import { cacheAdapterEnhancer, throttleAdapterEnhancer } from 'axios-extensions';

window.http = axios.create({
	baseURL: '/',
	headers: { 'Cache-Control': 'no-cache' },
	adapter: throttleAdapterEnhancer(axios.defaults.adapter, { threshold: 2 * 1000 })
});

require('./load-components')

import {VueMasonryPlugin} from 'vue-masonry';
Vue.use(VueMasonryPlugin)

import PortalVue from 'portal-vue';
Vue.use(PortalVue);

import VueCarousel from 'vue-carousel';
Vue.use(VueCarousel);

import VueLazyload from 'vue-lazyload';
Vue.use(VueLazyload)

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

// or with options
Vue.use(VueLazyload, {
  preLoad: 1.3,
  error: '/themes/wordplate/assets/images/loading.svg',
  loading: '/themes/wordplate/assets/images/loading.svg',
  attempt: 1,
  observer: true,
  observerOptions: {
    rootMargin: '0px',
    threshold: 0.1
  }
})

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
            console.info(payload);
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
