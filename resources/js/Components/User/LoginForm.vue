<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 animated fadeIn col-lg-6 center-screen">
                <div class="card w-90  p-4">
                    <form @submit.prevent="submit">
                        <div class="card-body">
                            <h4>SIGN IN</h4>
                            <br/>
                            <input id="login" v-model="form.login" placeholder="Email or Mobile" class="form-control" type="text"/>
                            <br/>
                            <input id="password" v-model="form.password"  placeholder="User Password" class="form-control" type="password"/>
                            <br/>
                            <button type="submit"  class="btn w-100 btn-success">Login</button>
                            <hr/>
                            <div class="float-end mt-3">
                            <span>
                                <a class="text-center ms-3 h6" href="/signup">Sign Up </a>
                                <span class="ms-1">|</span>
                                <a class="text-center ms-3 h6" href="/send-otp">Forget Password</a>
                            </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({
    position: "top-right",
});
const form = useForm({
    login: '',
    password: '',
});
const page = usePage();
function submit() {
    form.post('/userLogin', {
        onSuccess: () => {
            if(page.props.flash.status==true){
                toaster.success('Login successfully!', { timeout: 3000 });
                window.location.href = '/dashboard';
            }else if(page.props.flash.status==false){
                toaster.error('Login Failed !', { timeout: 3000 });
            }
        }
    });
}
</script>
