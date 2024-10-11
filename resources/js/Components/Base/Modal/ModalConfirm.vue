<script setup>
import { faTriangleExclamation } from '@fortawesome/free-solid-svg-icons';
import { computed, watch } from 'vue';

/**
 * * Variables
 */
const props = defineProps(['show', 'noAction', 'color', 'bg']);
const emit = defineEmits(["onClose", "onSubmit"]);
const propsShow = computed(() => props.show);

/**
 * * Hooks & Watcher
 */
watch(propsShow, (newValue) => {
  if (newValue) {
    document.getElementsByTagName("body")[0].style.overflow = "hidden";
  } else {
    document.getElementsByTagName("body")[0].style.removeProperty("overflow");
  }
})
</script>

<template>
    <div>
        <div :class="[
                'fixed left-0 z-20 w-screen h-screen bg-black opacity-50 modal-wrap',
                props.show ? 'top-0 opacity-1' : '-top-[110%] opacity-0'
            ]"
            @click="emit('onClose')">
        </div>

        <div :class="[
            'fixed left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white z-30 max-w-lg rounded-xl shadow-lg border-b-4 p-8 border-primary modalWidth',
            show ? 'top-1/2 opacity-1' : 'top-[150%] opacity-0'
        ]">

            <h3 :class="[
                    'mb-5 text-xl font-bold text-center',
                    props.color ? 'text-' + props.color : 'text-danger'
                ]">
                <slot name="title" />
            </h3>

            <slot name="content" />

            <div v-if="!noAction" class="flex justify-center gap-4 mt-7">
                <button class="py-2 px-5 bg-danger hover:opacity-70 text-white rounded" @click="emit('onClose')">No</button>
                <button class="py-2 px-5 bg-primary hover:opacity-70 text-white rounded" @click="emit('onSubmit')">Yes</button>
            </div>

            <!-- Icons -->
            <div :class="[
                'absolute top-0 left-1/2 z-30 max-w-lg py-5 -translate-x-1/2 -translate-y-full px-36 rounded-t-xl',
                props.bg ? 'bg-' + props.bg : 'bg-danger'
            ]">
                <FontAwesomeIcon :icon="faTriangleExclamation" class="text-3xl text-white" />
            </div>
        </div>
    </div>
</template>

<style scoped>
.modalWidth {
  /* width: 60vw; */
  min-width: 400px;
}
</style>