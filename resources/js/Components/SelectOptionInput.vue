<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    options: Array
})

const model = defineModel({
    type: Number,
    required: true
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select ref="input" v-model="model" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="" selected disabled>--Select--</option>
        <option v-for="item in props.options" :value="item.value">{{ item.label }}</option>
    </select>
</template>
