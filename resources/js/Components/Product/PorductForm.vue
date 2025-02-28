<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card animated fadeIn w-90  p-4 shadow-lg">
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="card-body">
                        <h4>Save Product</h4>
                        <br/>
                        <label>Product Name</label>
                        <input id="name" v-model="form.name"  placeholder="Product Name" class="form-control" type="text"/>
                        <label>Price</label>
                        <input id="price" v-model="form.price"  placeholder="Product price" class="form-control" type="text"/>
                        <label>Unit</label>
                        <input id="unit"  v-model="form.unit"  placeholder="Product unit" class="form-control" type="text"/>
                        <!-- Category Dropdown -->
                         <div>
                            <label for="categorie">Select Categorei:</label>
                            <select v-model="form.categorie_id" class="form-control" id="Categorie">
                                <option value="" disabled>Select a category</option>
                                <option v-for="page in Categories" :key="page.id" :value="page.id">{{ page.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="image">Product Image:</label>
                            <ImageUpload :productImage="form.img"  @img="(e) => (form.img = e)" />
                        </div>
                        <input type="id" v-model="form.id" class="form-control" hidden>
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
import { router, usePage ,useForm} from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";
import ImageUpload from './ImageUpload.vue';
import { ref } from "vue";


const UrlPreams=new URLSearchParams(window.location.search);
let id=ref(parseInt(UrlPreams.get('id')));
const page = usePage();

let Categories = page.props.categories;


const toaster = createToaster({
    position: "top-right",
});

const form = useForm({
    name: '',
    price: '',
    unit: '',
    categorie_id: '',
    id: id,
    img: '',
});
console.log(form);

let Url=('/addProduct');

if(id.value!==0){
    Url=('/updateProduct');
    form.name=page.props.product['name'];
    form.price=page.props.product['price'];
    form.unit=page.props.product['unit'];
    form.categorie_id=page.props.product['categorie_id'];
    form.id=page.props.product['id'];
    form.img=page.props.product['image_url'];

}
function submit() {
    form.post(Url, {
        onSuccess: () => {
            if (page.props.flash.status === true) {
                toaster.success(page.props.flash.message, { timeout: 3000 });
                router.visit('/product-page');
            } else if (page.props.flash.status === false) {
                toaster.error(page.props.flash.message, { timeout: 3000 });
            }
        },
    });
}
</script>
