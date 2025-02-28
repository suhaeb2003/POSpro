

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card animated fadeIn w-90  p-4 shadow-lg">
                    <form @submit.prevent="submit">
                    <div class="card-body">
                        <h4>Category</h4>
                        <br/>
                        <label>Category Name</label>
                        <input id="name" v-model="form.name"  placeholder="Name" class="form-control" type="text"/>
                        <input id="id" v-model="form.id"  placeholder="Category id" class="form-control" type="text" hidden/>
                        <br/>
                        <button type="submit"  class="btn w-100 btn-success">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, usePage, router } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";
import { ref } from 'vue';

const toaster = createToaster({
    position: "top-right",
});
const page=usePage();
const UrlPerams=new URLSearchParams(window.location.search);
let id=ref(parseInt(UrlPerams.get('id')));

const form=useForm({
    name:'',
    id:id
})

let URL='/createCategorie';

if(id.value !==0){
    URL='/updateCategorie';
    form.id=page.props.category.id;
    form.name=page.props.category.name;
}

function submit(){
    form.post(URL, {
        onSuccess: () => {
            if (page.props.flash.status == true) {
                toaster.success(page.props.flash.message, { timeout: 3000 });
                router.visit('/category');
            } else if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message, { timeout: 3000 });
            }
        }
    });
}
</script>