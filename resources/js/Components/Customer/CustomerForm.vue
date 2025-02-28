

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card animated fadeIn w-90  p-4 shadow-lg">
                    <form @submit.prevent="submit">
                    <div class="card-body">
                        <h4>Customer</h4>
                        <br/>
                        <label>Name</label>
                        <input id="name" v-model="form.name"  placeholder="Name" class="form-control" type="text"/>
                        <label>Email</label>
                        <input id="email" v-model="form.email"  placeholder="Customer Email" class="form-control" type="email"/>
                        <label>Mobile</label>
                        <input id="mobile" v-model="form.mobile"  placeholder="Customer Mobile" class="form-control" type="tel"/>
                        <input id="id" v-model="form.id"  placeholder="Customer Id" class="form-control" type="text" hidden/>
                        <br/>
                        <button type="submit" class="btn w-100 btn-success">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { Link, useForm, usePage,router } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";

import { ref } from 'vue';
let UrlPreams=new URLSearchParams(window.location.search);
let id=ref(parseInt(UrlPreams.get('id')));


const toaster = createToaster({
    position: "top-right",
})
const page=usePage();
const form = useForm({
    name: '',
    email: '',
    mobile: '',
    id:id
})

let Url='/customerCreate';

if(id.value!==0){
    Url='/customerUpdate';
    form.name=page.props.customer.name;
    form.email=page.props.customer.email;
    form.mobile=page.props.customer.mobile;
    form.id=page.props.customer.id;
}
function submit() {
    form.post(Url,{
        onSuccess: () => {
            if (page.props.flash.status === true) {
                toaster.success(page.props.flash.message, { timeout: 3000 });
                router.visit('/customer-page');
            } else if (page.props.flash.status === false) {
                toaster.error(page.props.flash.message, { timeout: 3000 });
            }
        }
    })
}
</script>
