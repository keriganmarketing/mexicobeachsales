<template>
    <div class="quick-search p-4 d-inline-block text-white shadow">
        <div>
        <fit-text :min=".5" :max="10" class="m-0 font-weight-light fit line1">Mexico Beach</fit-text>
        <fit-text :min=".5" :max="10" class="m-0 font-weight-light fit">St. Joe Beach &amp; WindMark</fit-text>
        <fit-text :min=".5" :max="10" class="m-0 font-weight-bold text-info fit">Real Estate</fit-text>
        </div>
        <form action="property-search">
        <input name="q" value="search" type="hidden" >
        <input type="hidden" name="sort" value="list_date|desc" >
        
        <div class="row d-flex align-items-center pt-3 px-1">
            <div class="col-12 col-sm mb-2 flex-grow-1">
                <omni-bar
                    v-model="omni"
                    :options="omniTerms"
                    :filter-function="applySearchFilter"
                    field-value=""
                ></omni-bar>
            </div>
            <div class="col-12 col-sm-auto mb-2">
                <button class="btn btn-block btn-primary">SEARCH</button>
            </div>
        </div>
        <input type="hidden" name="area" value="Any" >
        <input type="hidden" name="propertyType" value="Any" >
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            'searchTerms': {
                type: Object,
                default: () => {}
            }
        },
        data(){
            return {
                omni: null,
                omniTerms: [],
                baseUrl: 'https://navica.kerigan.com/api/v1/omnibar',
            }
        },
        watch: {
            omni: function (newOmni, oldOmni) {
                if (newOmni.length > 2) {
                    this.search();
                }
            }
        },
        methods: {
            applySearchFilter(search, omniTerms) {
                return omniTerms.filter(term => term.value.toLowerCase().includes(search.toLowerCase()))
            },
            search: _.debounce(
                function () {
                    console.log(this.omni);
                    let vm = this;
                    let config = {
                        method: 'get',
                        url: vm.baseUrl + '?search=' + vm.omni,
                    };
                    axios(config)
                        .then(response => {
                            vm.omniTerms = response.data;
                        })
                },
                100
            )
        }
    }
</script>
<style lang="scss" scoped>
.fit {
    text-transform: uppercase;
    line-height: 1em;

    &.line1 {
        line-height: 1.2em;
    }
}
</style>
