<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster();
let page = usePage();

const selectedProduct = ref([]);
const selectedCustomer = ref(null);

const ProductHeader = [
    { text: "Image", value: "image_url" },
    { text: "Name", value: "name" },
    { text: "Qty", value: "unit" },
    { text: "Action", value: "action" },
];

const CustomerHeader = [
    { text: "Name", value: "name" },
    { text: "Action", value: "action" },
];
const ProductItem = ref(page.props.products);
const CustomerItem = ref(page.props.customers);


const searchProductValue = ref("");
const searchCustomerValue = ref("");

const vatRate = ref(5);
const flatDiscount = ref(0);
const discountPercent = ref(0);
const total = ref(0);
const qty = ref(0);
const vatAmount = ref(0);
const discountAmount = ref(0);
const usePercentageDiscount = ref(false);

const addProductToSale = (id, image, name, price, productUnit) => {
    if (productUnit > 0) {
        // Check if product is in stock
        const product = {
            id: id,
            image: image,
            name: name,
            price: price,
            unit: 1,
            exitsQty: productUnit - 1,
            productPrice: price,
        };
        selectedProduct.value.push(product);
        calculateTotal();
    } else {
        toaster.warning(`${name} is out of stock!`);
    }
};

const addQty = (id) => {
    const product = selectedProduct.value.find((product) => product.id === id);
    if (product.exitsQty > 0) {
        product.unit++;
        product.exitsQty--; // Decrease available stock
        calculateTotal();
    } else {
        toaster.warning(`${product.name} is out of stock!`);
    }
};

const removeQty = (id) => {
    const product = selectedProduct.value.find((product) => product.id === id);
    if (product.unit > 1) {
        product.unit--;
        product.exitsQty++; // Increase available stock
        calculateTotal();
    }
};


const removeProductFromSale = (index) => {
    selectedProduct.value.splice(index, 1);
    calculateTotal();
};

const addCustomerToSale = (customer) => {
    selectedCustomer.value = customer;
};

const calculateTotal = () => {
    return selectedProduct.value.reduce(
        (sum, product) => sum + product.productPrice * product.unit,
        0
    );
};

const applyVat = () => {
    vatAmount.value = (calculateTotal() * vatRate.value) / 100;
    calculatePayable();
};

const removeVat = () => {
    vatAmount.value = 0;
    calculatePayable();
};

const applyDiscount = () => {
    if (usePercentageDiscount.value) {
        discountAmount.value = (calculateTotal() * discountPercent.value) / 100;
    } else {
        discountAmount.value = flatDiscount.value;
    }
    calculatePayable();
};

const removeDiscount = () => {
    discountAmount.value = 0;
    calculatePayable();
};

const calculatePayable = () => {
    const totalAmount = calculateTotal();
    payable.value = totalAmount + vatAmount.value - discountAmount.value;
};
const countqty = () => {
    qty.value = selectedProduct.value.reduce((total, product) => total + product.unit, 0);
};

const payable = ref(0);


const form = useForm({
    customer_id: "",
    product: "",
    vat: "",
    discount: "",
    payable: calculateTotal(),
    total: "",
});

const createInvoice = () => {
    if (!selectedCustomer.value) {
        toaster.warning('Please select a customer');
        return;
    }

    if (selectedProduct.value.length === 0) {
        toaster.warning('Please select at least one product');
        return;
    }

    form.product = selectedProduct.value;
    form.customer_id = selectedCustomer.value.id;
    form.total = calculateTotal(); // Total amount before VAT and Discount
    form.payable = payable.value;

    if (usePercentageDiscount.value) {
        form.discount = discountPercent.value;
    } else {
        form.discount = flatDiscount.value;
    }

    form.vat = vatRate.value;


    form.post("/createInvoice", {
        onSuccess: () => {
            if (page.props.flash.status === true) {
                toaster.success(page.props.flash.message);
                setTimeout(() => {
                    router.get("/invoice");
                }, 500);
            } else {
                toaster.warning(page.props.flash.message);
            }
        },
    });
};

</script>

<template>
    <div class="container-fluid">
        <div class="row">
            <!-- Billing Selection -->
            <div class="col-md-4 col-lg-4 p-2">
                <div class="card">
                    <div class="card-body">
                        <h4>Billed To</h4>
                        <div class="shadow-sm h-100 bg-white rounded-3 p-3 mt-4">
                            <div class="row">
                                <div class="col-8">
                                    <span class="text-bold text-dark">BILLED TO</span>
                                    <p class="fs-6 mx-0 my-1">
                                        Name: <span>{{ selectedCustomer?.name || "" }}</span>
                                    </p>
                                    <p class="fs-6 mx-0 my-1">
                                        Email: <span>{{ selectedCustomer?.email || "" }}</span>
                                    </p>
                                    <p class="text-xs mx-0 my-1">
                                        User ID: <span>{{ selectedCustomer?.id || "" }}</span>
                                    </p>
                                </div>
                                <div class="col-4">
                                    <p class="text-bold mx-0 my-1 text-dark">Invoice</p>
                                    <p class="text-xs mx-0 my-1">
                                        Date: {{ new Date().toISOString().slice(0, 10) }}
                                    </p>
                                </div>
                            </div>
                            <hr class="mx-0 my-2 p-0 bg-secondary" />
                            <div class="row">
                                <div class="col-12">
                                    <table class="table w-100">
                                        <thead>
                                            <tr class="text-xs">
                                                <th>Name</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-if="selectedProduct.length > 0"
                                                v-for="(product, index) in selectedProduct" :key="index"
                                                class="text-center">
                                                <td>{{ product["name"] }}</td>
                                                <td>{{ product["unit"] }}</td>
                                                <td>{{ product["price"] }}</td>
                                                <td>
                                                    <div class="btn-group mb-1" role="group"
                                                        aria-label="Basic mixed styles example">

                                                        <button @click="removeQty(product.id)"
                                                            class="btn btn-warning btn-sm">-</button>
                                                        <button @click="addQty(product.id)"
                                                            class="btn btn-success btn-sm">+</button>
                                                    </div>
                                                    <button @click="removeProductFromSale(index)"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr class="mx-0 my-2 p-0 bg-secondary" />
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-bold text-xs my-1 text-dark">
                                        Total: <i class="bi bi-currency-dollar"></i> {{ calculateTotal() }}
                                    </p>
                                    <p class="text-bold text-xs my-1 text-dark">
                                        VAT ({{ vatRate }}%): <i class="bi bi-currency-dollar"></i>
                                        {{ vatAmount }}
                                    </p>

                                    <div class="d-flex justify-content-between">
                                        <p>
                                            <button class="btn btn-info btn-sm my-1 bg-gradient-primary w-40"
                                                @click="applyVat">
                                                Apply VAT
                                            </button>
                                        </p>
                                        <p>
                                            <button class="btn btn-secondary btn-sm my-1 bg-gradient-primary w-40"
                                                @click="removeVat">
                                                Remove VAT
                                            </button>
                                        </p>
                                    </div>


                                    <p><span class="text-xxs">Discount Mode:</span></p>
                                    <select v-model="usePercentageDiscount">
                                        <option :value="false">Flat Discount</option>
                                        <option :value="true">Percentage Discount</option>
                                    </select>
                                    <p class="text-bold text-xs my-1 text-dark">
                                        Discount: <i class="bi bi-currency-dollar"></i> {{ discountAmount }}
                                    </p>
                                    <div v-if="!usePercentageDiscount">
                                        <span class="text-xxs">Flat Discount:</span>
                                        <input type="number" v-model="flatDiscount" class="form-control w-40" min="0" />
                                        <p>
                                            <button class="btn btn-warning btn-sm my-1 bg-gradient-primary w-40"
                                                @click="applyDiscount">
                                                Apply Flat Discount
                                            </button>
                                        </p>
                                    </div>
                                    <div v-else>
                                        <span class="text-xxs">Discount (%):</span>
                                        <input type="number" v-model="discountPercent" class="form-control w-40" min="0"
                                            max="100" step="0.25" />
                                        <p>
                                            <button class="btn btn-warning btn-sm my-1 bg-gradient-primary w-40"
                                                @click="applyDiscount">
                                                Apply Percentage Discount
                                            </button>
                                        </p>
                                    </div>
                                    <p>
                                        <button class="btn btn-secondary btn-sm my-1 bg-gradient-primary w-40"
                                            @click="removeDiscount">
                                            Remove Discount
                                        </button>
                                    </p>
                                    <hr class="mx-0 my-2 p-0 bg-secondary" />
                                    <p class="text-bold text-xs my-1 text-dark">
                                        Payable: <i class="bi bi-currency-dollar"></i> {{ payable }}
                                    </p>
                                    <p>
                                        <button class="btn btn-success btn-sm my-3 bg-gradient-primary w-40"
                                            @click="createInvoice">
                                            Confirm
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Selection -->
            <div class="col-md-4 col-lg-4 p-2">
                <div class="card">
                    <div class="card-body">
                        <h4>Select Product</h4>
                        <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm"
                            v-model="searchProductValue" type="text" />
                        <EasyDataTable buttons-pagination alternating :headers="ProductHeader" :items="ProductItem"
                            :rows-per-page="5" :search-value="searchProductValue" v-model="itemSeletected">
                            <template #item-image_url="{ image_url }">
                                <img :src="`${image_url}`" alt="Product Image" class="img-thumbnail"
                                    style="width: 50px; height: 50px" />
                            </template>
                            <template #item-action="{ id, image, name, price, unit }">
                                <button :class="['btn btn-sm', unit > 0 ? 'btn-success' : 'btn-warning']"
                                    :disabled="unit <= 0" @click="addProductToSale(id, image, name, price, unit)">

                                    <span v-if="unit > 0"><i class="fa-solid fa-cart-plus"></i> </span>
                                    <span v-else><i class="fa-solid fa-ban"></i></span>

                                </button>

                            </template>
                        </EasyDataTable>
                    </div>
                </div>
            </div>

            <!-- Customer Selection -->
            <div class="col-md-4 col-lg-4 p-2">
                <div class="card">
                    <div class="card-body">
                        <h4>Select Customer</h4>
                        <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm"
                            v-model="searchCustomerValue" type="text" />
                        <EasyDataTable buttons-pagination alternating :headers="CustomerHeader" :items="CustomerItem"
                            :rows-per-page="10" :search-field="searchCustomerField" :search-value="searchCustomerValue">
                            <template #item-action="{ id, name, email }">
                                <button class="btn btn-success btn-sm" @click="addCustomerToSale({ id, name, email })">
                                    <i class="fa-solid fa-user-plus"></i>
                                </button>
                            </template>
                        </EasyDataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
