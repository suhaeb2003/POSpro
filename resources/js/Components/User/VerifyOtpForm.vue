
 
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card animated fadeIn w-90  p-4 shadow-lg">
                    <form @submit.prevent="submit">
                    <div class="card-body">
                        <h4>ENTER OTP CODE</h4>
                        <br/>
                        <label>4 Digit Code Here</label>
                        <input id="otp" v-model="form.otp"  placeholder="Code" class="form-control" type="text"/>
                        <br/>
                        <!-- <button type="submit"  class="btn w-100 btn-success">Next</button> -->
                        <button type="submit" class="btn w-100 btn-success">Next</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

 <script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({
    position: "top-right",
});
const page = usePage();
const form = useForm({
    otp: '',
})
function submit() {
    form.post('/verifyOTP', {
        onSuccess: () => {
            if (page.props.flash.status == true) {
                toaster.success(page.props.flash.message, { timeout: 3000 });
                router.visit('/reset-password');
            } else if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message, { timeout: 3000 });
            }
        }
    });
}

</script>
