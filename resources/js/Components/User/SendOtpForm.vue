<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card animated fadeIn w-90 p-4 shadow-lg">
                    <form @submit.prevent="submit">
                        <div class="card-body">
                            <h4>EMAIL ADDRESS</h4>
                            <br />
                            <label>Your email address</label>
                            <input
                                id="email"
                                v-model="form.email"
                                placeholder="User Email"
                                class="form-control"
                                type="email"
                            />
                            <br />
                            <!-- <button type="submit"  class="btn w-100 btn-success">Next</button> -->
                            <button type="submit" class="btn w-100 btn-success">
                                Next
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({
    position: "top-right",
});
const page = usePage();
const form = useForm({
    email: "",
});
function submit() {
    if (form.email.length == 0) {
        toaster.error("Please enter email address", { timeout: 3000 });
    } else {
        form.post("/sendOTP", {
            onSuccess: () => {
                if (page.props.flash.status == true) {
                    toaster.success(page.props.flash.message, {
                        timeout: 3000,
                    });
                    router.visit("/verify-otp");
                } else if (page.props.flash.status == false) {
                    toaster.error(page.props.flash.message, { timeout: 3000 });
                }
            },
        });
    }
}
</script>
