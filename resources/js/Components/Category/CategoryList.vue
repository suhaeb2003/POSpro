<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <input
                                    placeholder="Search..."
                                    class="form-control mb-2 w-auto form-control-sm"
                                    type="text"
                                    v-model="searchValue"
                                />
                                <Link
                                    href="/add-category?id=0"
                                    class="btn btn-success"
                                    >Add Category</Link
                                >
                            </div>

                            <EasyDataTable
                                buttons-pagination
                                alternating
                                :headers="Header"
                                :items="Item"
                                :rows-per-page="10"
                                :search-field="searchField"
                                :search-value="searchValue"
                                showIndex
                            >
                                <template #item-number="{ id }">
                                    <Link
                                        class="btn btn-success mx-3 btn-sm"
                                        :href="`/add-category?id=${id}`"
                                        ><i class="fa-solid fa-pen-to-square"></i></Link
                                    >
                                    <button
                                        class="btn btn-danger btn-sm"
                                        @click="DeleteClick(id)"
                                    >
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
const toaster = createToaster({
    position: "top-right",
});
const page = usePage();
const searchValue = ref("");

import { ref } from "vue";

const Header = [
    { text: "Name", value: "name"},
    { text: "Action", value: "number" }
];

const Item = ref(page.props.categorys);
if (page.props.flash.status == true) {
    toaster.success(page.props.flash.message, { timeout: 3000 });
    router.get("/category");
}
if (page.props.flash.status == false) {
    toaster.error(page.props.flash.message, { timeout: 3000 });
}
const DeleteClick = (id) => {
    let text = "Are you sure?";
    if (confirm(text) == true) {
        router.get(`/deleteCategorie/${id}`);
    } else {
        text = "You pressed Cancel!";
    }
};
</script>
