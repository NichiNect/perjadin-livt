<script setup>
import { ref } from 'vue';
/**
 * * Variables
 */
const props = defineProps([
    'type', 'variant', 'color', 'class', 'label', 'size', 'disabled', 'rounded', 'loading'
]);
const emit = defineEmits(["onClick"]);
const btn = ref(null);

const buttonVariant = {
    solid: {
        primary: 'bg-primary text-white',
        lightPrimary: 'bg-lightPrimary text-white',
        secondary: 'bg-secondary text-white',
        danger: 'bg-danger text-white',
        success: 'bg-success text-white',
        warning: 'bg-warning text-white',
    },
    outline: {
        primary: 'border-2 border-primary text-primary',
        lightPrimary: 'border-2 border-lightPrimary text-lightPrimary',
        secondary: 'border-2 border-secondary text-secondary',
        danger: 'border-2 border-danger text-danger',
        success: 'border-2 border-success text-success',
        warning: 'border-2 border-warning text-warning',
    },
    light: {
        primary: 'bg-lightPrimary text-primary shadow',
        secondary: 'bg-lightSecondary text-secondary shadow',
        danger: 'bg-lightDanger text-danger shadow',
        success: 'bg-lightSuccess text-success shadow',
        warning: 'bg-lightWarning text-warning shadow',
    },
    lightBordered: {
        primary: 'bg-lightPrimary text-primary shadow border',
        secondary: 'bg-lightSecondary text-secondary shadow border',
        danger: 'bg-lightDanger text-danger shadow border',
        success: 'bg-lightSuccess text-success shadow border',
        warning: 'bg-lightWarning text-warning shadow border',
    },
    squareIcon: {
        primary: 'bg-primary text-white shadow border',
        secondary: 'bg-secondary text-white shadow border',
        danger: 'bg-danger text-white shadow border',
        success: 'bg-success text-white shadow border',
        warning: 'bg-warning text-white shadow border',
    }
}
const buttonSize = {
    lg: 'px-10 py-3 flex items-center justify-center gap-3',
    md: 'px-8 py-2 flex items-center justify-center gap-2',
    sm: 'px-6 py-2 text-sm flex items-center justify-center gap-2',
    xs: 'px-4 py-1 text-xs flex items-center justify-center gap-1.5',
    square: {
        lg: 'aspect-square w-[47px] flex items-center justify-center gap-3',
        md: 'aspect-square w-[40px] flex items-center justify-center gap-2',
        sm: 'aspect-square w-[35px] flex items-center justify-center gap-2',
        xs: 'aspect-square w-[20px] text-xs flex items-center justify-center gap-1.5',
    }
}

/**
 * * Methods
 */
const clickHandler = () => {
  emit('onClick');
}
</script>

<template>
    <button
        :ref="btn" 
        :type="props.type ?? 'button'"
        :class="[
            'btn',
            buttonVariant[props.variant ?? 'solid'][props.color ?? 'primary'],
            (props.variant == 'squareIcon') ? buttonSize['square'][props.size ?? 'md'] : buttonSize[props.size ?? 'md'],
            props.rounded ? `rounded-${props.rounded}` : 'rounded-lg',
            props.loading ? 'opacity-70' : '',
            props.class ?? '',
        ]"
        :disabled="(props.disabled || props.loading) ? true : false"
        @click="clickHandler"
    >
        <div v-if="loading" :class="[
            props.loading ? 'btn_loading' : '',
            'w-3 h-3 justify-center text-center'
        ]"></div>
        <span v-if="props.label">{{ props.label }}</span>
        <span v-else>
            <div v-if="props.variant == 'squareIcon' && !props.loading">
                <slot />
            </div>
            <div v-else-if="props.variant != 'squareIcon'">
                <slot />
            </div>
        </span>
    </button>
</template>

<style scoped>
.btn {
  @apply whitespace-nowrap;
	transform: scale(1);
	filter: brightness(0.95);
}

.btn:disabled {
	/* @apply bg-lightDisable text-disable; */
	cursor: default;
	pointer-events: none;
}

.btn:hover {
	transform: scale(1.02);
	filter: brightness(1);
	box-shadow: 0 2px 5px 0 rgba(0, 0, 0, .15);
}

.btn:active {
	transform: scale(1);
	filter: brightness(0.9);
	box-shadow: inset 0 0 5px 0 rgba(0, 0, 0, .25);
}

.btn_loading {
  border-radius: 50%;
  display: inline-block;
  border-top: 2.5px solid;
  border-left: 2.5px solid;
  border-right: 2.5px solid transparent;
  box-sizing: border-box;
  animation: rotation 1s linear infinite;
}

@keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
} 
</style>