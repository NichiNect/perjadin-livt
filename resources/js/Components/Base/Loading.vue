<script setup>
import { faSpinner } from '@fortawesome/free-solid-svg-icons';

</script>

<template>
    <div class="flex justify-center items-center gap-2">
        <FontAwesomeIcon :icon="faSpinner" spin class="w-5 font-semibold" />
        <div class="text-lg">Loading...</div>
    </div>
</template>