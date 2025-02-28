
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card animated fadeIn w-100 p-3 center-screen">
                    <form @submit.prevent="submit()">
                       
                        <div class="card-body shadow-lg ">
                            <h4>Create Account</h4>
                            <hr/>
                            <div class="container-fluid m-0 p-0">
                                <div class="row m-0 p-0">
                                    <div class="col-md-4 p-2">
                                        <label>First Name</label>
                                        <input id="firstname" v-model="form.firstname" placeholder="First Name" class="form-control" type="text"/>
                                    </div>
                                    <div class="col-md-4 p-2">
                                        <label>Last Name</label>
                                        <input id="lastname" v-model="form.lastname" placeholder="Last Name" class="form-control" type="text"/>
                                    </div>

                                    <div class="col-md-4 p-2">
                                        <label>Email Address</label>
                                        <input id="email" v-model="form.email"  placeholder="User Email" class="form-control" type="email"/>
                                    </div>

                                    <div class="col-md-4 p-2">
                                        <label>Mobile Number</label>
                                        <input id="mobile" v-model="form.mobile" placeholder="017xxxxxxxx" class="form-control" type="tel" />
                                    </div>
                                    <div class="col-md-4 p-2">
                                        <label>Password</label>
                                        <input id="password" v-model="form.password"  placeholder="User Password" class="form-control" type="password"/>
                                    </div>
                                </div>
                                <div class="row m-0 p-0">
                                    <div class="col-md-4 p-2">
                                        <button type="submit"  class="btn mt-3 w-100  btn-success">Sign Up</button>
                                    </div>
                                </div>
                            </div>
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

const page=usePage();
const form = useForm({
    'firstname': '',
    'lastname': '',
    'email': '',
    'mobile': '',
    'password': ''
});
function submit() {
    form.post('/user-registration', {
        onSuccess: () => {
            if(page.props.flash.status==true){
                toaster.success(page.props.flash.message, { timeout: 3000 });
                router.visit('/login-page');
            }else if(page.props.flash.status==false){
                toaster.error(page.props.flash.message, { timeout: 3000 });
            }
        }
    });
}
</script>

