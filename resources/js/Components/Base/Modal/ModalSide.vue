<script setup>
import { computed, watch } from 'vue';
import { faTimes } from '@fortawesome/free-solid-svg-icons';

/**
 * * Variables
 */
const props = defineProps(['show', 'width']);
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
});
</script>

<template>
  <div>
    <div :class="[
      'fixed top-0 z-40 w-screen h-screen bg-black opacity-50 modal-wrap',
      props.show ? 'left-0 opacity-1' : '-left-[110%] opacity-0'
    ]"
    @click="emit('onClose')">
    </div>

    <div 
      :class="[
        'fixed top-0',
        props.show ? 'right-0' : '-right-[100%]',
        'h-screen bg-white z-50 rounded-l-3xl border-l-4 border-secondary shadow-[-4px_0px_10px_-4px_rgba(0,0,0,0.35)]'
      ]" 
      :style="[
        props.width ? `width: ${props.width};` : 'width: 30%;'
      ]"
    >

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
      <div class='px-8 max-h-[calc(100vh-75px)] overflow-y-auto scroll_control'>
        <div class="min-h-[calc(100vh-75px)] mb-12">
          <slot name="content" />
        </div>
      </div>

      <!-- Footer -->
      <div class="px-8 py-4 mt-12 bg-background rounded-b-xl absolute bottom-0 w-full">
        <slot name="footer" />
      </div>
    </div>
  </div>
</template>

<style scoped>
.modalWidth {
  width: 80%;
}
</style>