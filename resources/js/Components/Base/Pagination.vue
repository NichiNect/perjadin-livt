<script setup>
import { ref, watchEffect } from 'vue';
import { faChevronLeft, faChevronRight } from '@fortawesome/free-solid-svg-icons';

/**
 * * Variables
 */
const props = defineProps([
    'total', 'paginateRow', 'pageActive',
]);
const emit = defineEmits(["onChange"]);

const paginateData = ref({
    isFirst: false,
    pages: [1],
    isLast: false,
    totalPage: 1
});

/**
 * * Methods
 */
const paginateChangeHandler = () => {

  if (props.total > props.paginateRow) {

    let showPages = [];
    let totalPage = Math.ceil(props.total / props.paginateRow);
  
    if (props.pageActive > 1 && props.pageActive < totalPage) {
  
        if (props.pageActive > 2) {
            showPages = [props.pageActive - 1, props.pageActive, props.pageActive + 1];
        } else {
            showPages = [1, props.pageActive, props.pageActive + 1];
        }
    } else if (props.pageActive <= 1 && totalPage > 2) {
  
        showPages = [props.pageActive, props.pageActive + 1, props.pageActive + 2];
    } else if (totalPage == 2) {
  
        if (props.pageActive == 2) {
            showPages = [1, props.pageActive];
        } else {
            showPages = [props.pageActive, 2];
        }
    } else {
  
        showPages = [props.pageActive - 2, props.pageActive - 1, props.pageActive];
    }
  
    paginateData.value = {
        isFirst: props.pageActive > 3,
        pages: showPages,
        isLast: props.pageActive + 1 < totalPage && totalPage > 3,
        totalPage: totalPage
    }

  } else {

    paginateData.value = {
        isFirst: false,
        pages: [1],
        isLast: false,
        totalPage: 1
    }
  }
}

/**
 * * Hooks & Watcher
 */
// watch(
//   [() => props.paginateRow, () => props.pageActive],
//   () => {
//   console.log('called');
//   paginateChangeHandler();
// });

watchEffect(() => {
    paginateChangeHandler();
});

</script>

<template>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div 
                v-if="props.pageActive > 1"
                class="p-3 text-gray-400 cursor-pointer"
                @click="emit('onChange', {
                total: props.total,
                paginateRow: props.paginateRow,
                pageActive: props.pageActive - 1
                });"
            >
                <FontAwesomeIcon :icon="faChevronLeft" class="w-2" />
            </div>

            <div 
                v-if="paginateData.isFirst" class="px-3 py-1.5 bg-white rounded-md cursor-pointer hover:scale-105"
                @click="emit('onChange', {
                total: props.total,
                paginateRow: props.paginateRow,
                pageActive: 1
                });"
            >
                1
            </div>
            <div v-if="paginateData.isFirst" class="px-1 py-1.5 rounded-md">...</div>

            <div
                v-if="paginateData.pages?.length > 0"
                v-for="(page, idx) in paginateData.pages"
                :key="idx"
                :class="[
                'py-1.5 px-3 rounded-md',
                page == props.pageActive ? 'bg-lightPrimary text-primary' : 'bg-white cursor-pointer',
                'hover:scale-105'
                ]"
                @click="emit('onChange', {
                total: props.total,
                paginateRow: props.paginateRow,
                pageActive: page 
                });"
            >
                {{ page }}
            </div>

            <div v-if="paginateData.isLast" class="px-1 py-1.5 rounded-md">...</div>
            <div 
                v-if="paginateData.isLast" 
                :class="[
                'py-1.5 px-3 rounded-md',
                paginateData.totalPage == props.pageActive ? 'bg-lightPrimary text-primary' : 'bg-white cursor-pointer',
                'hover:scale-105'
                ]"
                @click="emit('onChange', {
                total: props.total,
                paginateRow: props.paginateRow,
                pageActive: paginateData.totalPage
                });"
            >
                {{ paginateData.totalPage }}
            </div>

            <div
                v-if="paginateData.totalPage && props.pageActive < paginateData.totalPage"
                class="p-3 text-gray-400 cursor-pointer"
                @click="emit('onChange', {
                total: props.total,
                paginateRow: props.paginateRow,
                pageActive: props.pageActive + 1
                });"
            >
                <FontAwesomeIcon :icon="faChevronRight" class="w-2" />
            </div>
        </div>

        <div class="flex items-center gap-5 px-5">
        <div class="text-gray-400">
            <div>
                {{ props.paginateRow * props.pageActive - props.paginateRow + 1 }} - 
                {{ props.pageActive < paginateData.totalPage ? props.paginateRow * props.pageActive : props.total }}
                from
                {{ props.total }}
            </div>
        </div>
        </div>
    </div>
</template>