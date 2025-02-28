<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card animated fadeIn w-90 p-4 shadow-lg">
                    <form @submit.prevent="submit()">
                        <div class="card-body">
                            <h4>SET NEW PASSWORD</h4>
                            <br />
                            <label>New Password</label>
                            <input
                                id="password"
                                v-model="form.password"
                                placeholder="New Password"
                                class="form-control"
                                type="password"
                            />
                            <br />
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
import { useForm, usePage, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({
    position: "top-right",
});
const form = useForm({
    password: "",
});
const page = usePage();
function submit() {
    if (form.password.length == 0) {
        toaster.error("Password is required", { timeout: 3000 });
    }else{
        form.post("/resetPass", {
        onSuccess: () => {
                if (page.props.flash.status == true) {
                    toaster.success(page.props.flash.message, {
                        timeout: 3000,
                    });
                    router.visit("/login-page");
                } else if (page.props.flash.status == false) {
                    toaster.error(page.props.flash.message, { timeout: 3000 });
                }
        },
    });
    }
    
}
</script>
