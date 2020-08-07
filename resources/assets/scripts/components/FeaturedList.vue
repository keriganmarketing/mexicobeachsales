<template>
    <div>
        <listing-large
            v-for="listing in list"
            :key="listing.index"
            :mls_number="listing.mls_number"
            :title="listing.post_title"
            :order="listing.menu_order"
            @closeviewer="closeGallery"
            @openviewer="openGallery"
        ></listing-large>
    </div>    
</template>
<script>
require('es6-promise').polyfill();
import axios from 'axios'

export default {
    props: {
        type: '',
        limit: 0,
    },

    data(){
        return {
            list: {}
        }
    },

    created() {
        this.getListings()
    },

    methods: {
        getListings(){
            axios.get("/wp-json/kerigansolutions/v1/list?type=" + this.type + '&limit=' + this.limit)
                .then(response => {
                    this.list = response.data;
                });
        },

        openGallery(payload){
            this.$emit('openGallery',payload)
        },

        closeGallery(){
            this.$emit('closeGallery',payload)
        }
    }
}
</script>