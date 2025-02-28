<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm" type="text" v-model="searchValue">
                                <Link href="/create-seal" class="btn btn-success">Add Invoice</Link>
                            </div>
                            
                            <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item" :rows-per-page="10" :search-field="searchField"  :search-value="searchValue" show-index>
                                <template #item-number="{ id }">
                                    <button class="btn btn-danger btn-sm" @click="DeleteClick(id)"><i class="fa-solid fa-trash"></i></button>
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
import { Link, usePage,router } from "@inertiajs/vue3";
const page=usePage();
import {ref} from "vue";
const searchValue = ref("");


const Header = [
    { text: "Name", value: "customer.name"},
    { text: "Total", value: "total"},
    { text: "Discount", value: "discount"},
    { text: "Vat", value: "vat"},
    { text: "Payable", value: "payable"},
    { text: "Action", value: "number"},
];


const Item = ref(page.props.invoices);


const DeleteClick = (id) => {
    router.get(`/deleteInvoice/${id}`);
    if(page.props.flash.status==true){
    toaster.success(page.props.flash.message, { timeout: 3000 });
    router.get('invoice');
    }   
    if(page.props.flash.status==false){
        toaster.error(page.props.flash.message, { timeout: 3000 });
    }
};


</script>
