<template>
    <div>
        <lazy-component @show="loadHandler" style="min-height:10px;" >
        </lazy-component>

            <h2 class="display-3 pt-4">
            #{{ order }}: {{ title }}
            {{ listingData.sub_area ? ' - ' + listingData.sub_area : '' }}
            </h2>

            <div 
                v-if="loaded === false"
                class="loading d-flex align-items-center justify-content-center border my-3 h-100" 
                style="min-height:500px;"
                > <p class="m-0">loading...</p>
            </div>

            <div v-if="loaded === true && noData === false">
                <h3 v-if="isNew()" 
                    style="font-size:16px;" 
                    class="badge badge-info d-inline-block mr-2">
                    Just Listed</h3>
                
                <h3 v-if="listingData.status == 'Sold/Closed' && listingData.sold_for != null" 
                    style="font-size:16px;" 
                    class="badge badge-info d-inline-block mr-2">
                    Sold on {{ listingData.sold_on }}
                    for ${{ listingData.sold_for.toLocaleString('en-US') }}</h3>

                <h3 v-if="listingData.status == 'Contingent'" 
                    style="font-size:16px;" 
                    class="badge badge-info d-inline-block mr-2">
                    SALE CONTINGENT</h3>

                <h3 v-if="isReduced() && listingData.price != null" 
                    style="font-size:16px;" 
                    class="badge badge-danger d-inline-block mr-2">
                    REDUCED <span style="text-decoration:line-through">
                        ${{ listingData.original_list_price.toLocaleString('en-US') }}</span> 
                        <strong>${{listingData.price.toLocaleString('en-US') }}</strong></h3>

                <h3 style="font-size:16px;" 
                    class="badge badge-secondary d-inline-block mr-2">
                    MLS# {{ mls_number }}</h3>

                <div class="row mb-5 pt-4">
                    <div class="col-md-4">
                        <img :src="listingData.media_objects.data[0].url" class="img-fluid shadow" >
                        <photo-gallery 
                        :mls-account="parseInt(mls_number)"
                        @closeviewer="closeGallery"
                        @openviewer="openGallery"
                        class="d-none d-sm-block"
                        :viewer-state="galleryIsOpen"
                        :virtual-tour="listingData.virtual_tour" 
                        :data-photos="listingData.media_objects.data"
                        item-class="col-sm-6"
                        :limit="5" ></photo-gallery>
                    </div>
                    <div class="col-md-8">

                        <div class="pt-4 row">
                            <div class="col-12 col-lg-auto">
                                <a class="btn btn-block d-sm-inline-block btn-primary mb-4" 
                                    :href="'/listing/' + mls_number" >
                                    View Listing Details</a> 
                            </div>
                            <div v-if="listingData.virtual_tour != '' && listingData.status != 'Sold/Closed'" class="col-6 col-lg-auto">
                                <a class="btn btn-block btn-secondary mb-4" 
                                    target="_blank" :href="'//' + listingData.virtual_tour" >
                                    Virtual Tour</a> 
                            </div>
                            <div v-if="listingData.media_objects.data.length > 1" class="col-6 col-lg-auto">
                                <a @click="openGallery(mls_number)" 
                                    class="btn btn-block btn-secondary mb-4 pointer text-white" >
                                    Photos ({{listingData.media_objects.data.length }})</a>
                            </div>
                        </div>

                        <div class="card">
                            <table class="table table-striped m-0">
                            <tr v-if="listingData.price != null">
                                <td>Price</td><td>${{ price.toLocaleString('en-US') }}</td>
                            </tr>
                            
                            <tr v-if="listingData.full_address != ''">
                                <td>Address</td><td>{{ listingData.full_address }}</td>
                            </tr>
                            <tr v-if="listingData.bedrooms != '' && listingData.bedrooms != 0 && listingData.bedrooms != null">
                                <td>Bedrooms</td><td>{{ listingData.bedrooms }}</td>
                            </tr>   
                            <tr v-if="listingData.full_baths != '' && listingData.full_baths != 0 && listingData.full_baths != null">
                                <td>Full Bathrooms</td><td>{{ listingData.full_baths }}</td>
                            </tr>
                            <tr v-if="listingData.half_baths != '' && listingData.half_baths != 0 && listingData.half_baths != null">
                                <td>Half Bathrooms</td><td>{{ listingData.half_baths }}</td>
                            </tr>
                            <tr v-if="listingData.acreage != '' && listingData.acreage != 0">
                                <td>Acreage</td><td>{{ listingData.acreage }} Acres</td>
                            </tr>
                            <tr v-if="listingData.total_hc_sqft != '' && listingData.total_hc_sqft != 0 && listingData.total_hc_sqft != null">
                                <td>H/C SqFt</td><td>{{ listingData.total_hc_sqft }} SqFt</td>
                            </tr>
                            <tr v-if="listingData.sqft != '' && listingData.sqft != 0 && listingData.sqft != null">
                                <td>Total SqFt</td><td>{{ listingData.sqft }} SqFt</td>
                            </tr>
                            <tr v-if="listingData.lot_dimensions != '' && (listingData.lot_dimensions != '0' || listingData.lot_dimensions != null)">
                                <td>Lot Size</td><td>{{ listingData.lot_dimensions }}</td>
                            </tr>
                            <tr v-if="listingData.list_date != null">
                                <td>List Date</td><td>{{ listingData.list_date }}</td>
                            </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div 
                v-if="loaded === true && noData === true" 
                class="loading d-flex align-items-center justify-content-center border my-3 h-100" 
                style="min-height:200px;"
                > <p class="m-0">listing no longer on market</p>
            </div>
        
    </div>
</template>
<script>
require('es6-promise').polyfill();
import axios from 'axios'
import moment from 'moment'

export default {
    props: {
        mls_number: '',
        order: '',
        title: ''
    },

    data(){
        return {
            loaded: false,
            listingData: {
                hasData: false,
            },
            galleryIsOpen: '',
            isOnScreen: false
        }
    },

    mounted() {
        this.loaded = false
    },

    computed: {
        price(){
            return this.listingData.price
        },
        noData(){
            return this.listingData.hasData == false && this.loaded == true
        },
        listDate(){
            return this.listingData.list_date
        }
    },

    methods: {

        loadHandler(data){
            this.getListingData()
            this.isOnScreen = data.show
        },

        getListingData(){
            let vm = this

            axios.get("/wp-json/kerigansolutions/v1/listing?mls=" + this.mls_number)
                .then(response => {
                    vm.listingData = response.data;

                    vm.$nextTick(() => {
                        if(vm.listingData.price != undefined){
                            vm.listingData.hasData = true
                        }else{
                            vm.listingData.hasData = false
                        }
                        vm.loaded = true
                    })
                                       
                });
        },

        isNew(){
            return moment(this.listDate).isAfter(moment().subtract(10, 'days'))
        },

        isReduced(){            
            return this.listingData.original_list_price > this.listingData.price && this.listingData.status == 'Active' && this.listingData.original_list_price != 0;
        },

        openGallery(payload){
            this.galleryIsOpen = payload
            this.$root.openGallery(payload)
        },

        closeGallery(payload){
            this.$root.galleryIsOpen = ''
        }


    }
}
</script>