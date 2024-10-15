<script setup>
import { faTimes } from '@fortawesome/free-solid-svg-icons';
import { computed, watch } from 'vue';

/**
 * * Variables
 */
const props = defineProps(['show']);
const emit = defineEmits(["onClose"]);
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
            'fixed left-0 z-20 w-screen h-screen bg-black opacity-50 modal-wrap overflow-auto',
            props.show ? 'top-0 opacity-1' : '-top-[110%] opacity-0'
        ]"
            @click="emit('onClose')">
        </div>

            <div :class="[
                'fixed left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white z-30 max-w-lg rounded-xl shadow-lg border-b-4 border-primary modalWidth',
                show ? 'top-1/2 opacity-1' : 'top-[150%] opacity-0'
            ]">

            <!-- Header/Title -->
            <div class="flex justify-between items-center gap-8 px-8 py-6">
                <div>
                    <slot name="title" />
                </div>

                <button class="py-1 px-3 rounded aspect-square hover:bg-gray-200 hover:shadow-md"
                    @click="emit('onClose')"
                >
                    <FontAwesomeIcon :icon="faTimes" />
                </button>
            </div>

            <!-- Body/Content -->
            <div class='px-8 pb-8'>
                <slot name="content" />
            </div>

            <!-- Footer -->
            <div class='px-8 py-4 bg-background rounded-b-xl'>
                <slot name="footer" />
            </div>
        </div>
    </div>
</template>

<style scoped>
.modalWidth {
  width: 60vw;
  max-width: 600px;
}
</style>