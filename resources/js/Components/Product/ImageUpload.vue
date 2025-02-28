<script setup>
import { ref } from "vue";

const props = defineProps({
    productImage: String,
});
const currentImage = props.productImage ? props.productImage : '';
const preview = ref(currentImage);
const emit = defineEmits(["img"]);

const imageSelected = (e) => {
    preview.value = URL.createObjectURL(e.target.files[0]);
    emit("img", e.target.files[0]);
};
</script>
<template>
    <div>
        <label for="image">
            <img
                :src="(preview || currentImage) ?? '/placeholder.png'"
                class="img-thumbnail"
                height="50px"
                width="50px"
                alt="Product Image"
            />
        </label>
        <input @input="imageSelected($event)" type="file" name="image" id="image" />
    </div>
</template>
