<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm"
                                    type="text" v-model="searchValue" />
                                <Link href="/product-add?id=0" class="btn btn-success">Add Product</Link>
                            </div>
                            <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item"
                                :rows-per-page="10" :search-field="searchField" :search-value="searchValue"
                                :sort-by="sortBy" show-index index-column="S.L">
                                <template #item-image_url="{ image_url }">
                                    <img :src="`${image_url}`" alt="Product Image" class="img-thumbnail"
                                        style="width: 50px; height: 50px" />
                                </template>
                                <template #item-number="{ id }">
                                    <Link :href="`/product-add?id=${id}`" class="btn btn-success mx-3 btn-sm"><i class="fa-solid fa-pen-to-square"></i>
                                    </Link>
                                    <button class="btn btn-danger btn-sm" @click="DeleteItem(id)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </template>
                            </EasyDataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, usePage, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({ position: "top-right" });
const page = usePage();
import { ref } from "vue";

const Header = [
    { text: "Name", value: "name" },
    { text: "Price", value: "price", sortable: true },
    { text: "Unit", value: "unit" },
    { text: "Category", value: "categorie.name" },
    { text: "Image", value: "image_url" },
    { text: "Action", value: "number" },
];

const Item = ref(page.props.products);
const searchValue = ref("");
const sortBy = ref("");
if (page.props.flash.status == false) {
    toaster.error(page.props.flash.message, { timeout: 3000 });
}
const DeleteItem = (id) => {
    if (confirm("Are you sure you want to delete this item?")) {
        router.get(`/deleteProduct/${id}`);
    }
    if (page.props.flash.status == false) {
        toaster.error(page.props.flash.message, { timeout: 3000 });
    }
    if (page.props.flash.status == true) {
        toaster.success(page.props.flash.message, { timeout: 3000 });
    }
};
const itemClick = (number, player) => {
    alert(`Number is=${number} & Player Name is=${player}`);
};
</script>
