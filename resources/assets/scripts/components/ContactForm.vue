<template>
    <div>
        <form>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="sr-only" for="name-field">Name</label>
                        <input
                            type="text"
                            id="name-field"
                            class="form-control border-0"
                            placeholder="Name"
                            v-model="form.name"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="email-field">Email Address</label>
                        <input
                            type="email"
                            id="email-field"
                            class="form-control border-0"
                            placeholder="Email Address"
                            v-model="form.email"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="phone-number-field">Phone Number</label>
                        <input
                            type="tel"
                            id="phone-number-field"
                            class="form-control border-0"
                            placeholder="Phone Number"
                            v-model="form.phone"
                            required
                        >
                    </div>


                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="sr-only" for="message-field">Message</label>
                        <textarea
                            id="message-field"
                            class="form-control border-0"
                            placeholder="Message"
                            v-model="form.comments"
                        >
                        </textarea>
                    </div>
                    <invisible-recaptcha sitekey="6LcirZUUAAAAAP0438I86dmC5E4Ch-6nlo41_9RT" :callback="formSubmitted"
                        class="btn btn-primary btn-block" type="submit" id="contact-form-submit-button" >
                        Send Message &nbsp; <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </invisible-recaptcha>
                </div>

            </div>
        </form>
    </div>
</template>
<script>
import InvisibleRecaptcha from 'vue-invisible-recaptcha';
import ContactForm from '../models/contact-form';
    export default {
        components: {
            'invisible-recaptcha': InvisibleRecaptcha
        },
        props: ['listing'],
        data () {
            return {
                form: new ContactForm({
                    name: '',
                    email: '',
                    phone: '',
                    comments: '',
                    url: '/wp-json/kerigansolutions/v1/submit-contact-form',
                    listing: '',
                    image: '',
                    address: '',
                    price: ''
                })
            }
        },
        computed: {
            hasError: function() {
                return this.form.hasError;
            },
            errorCode: function () {
                return this.form.errorCode;
            },
            success: function () {
                return this.form.success;
            }
        },
        mounted () {
            if(this.listing != ''){
                this.form.listing = this.listing.mls_account;
                this.form.image   = this.listing.media_objects.data[0].url;
                this.form.address = this.listing.full_address;
                this.form.price   = this.listing.price;
            }
        },
        methods: {
            formSubmitted () {
                this.form.submit();
            }
        }
    }
</script>
