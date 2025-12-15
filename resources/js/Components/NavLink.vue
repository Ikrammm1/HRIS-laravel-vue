<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        default: false,
    },
});

const route = useRoute();

const isActive = computed(() => {
    return props.active || route.path === props.href;
});

const classes = computed(() =>
    isActive.value
        ? 'relative inline-flex items-center p-3 text-sm font-medium rounded-xl transition-all overflow-hidden bg-teal-600 text-white shadow-md before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-8 before:bg-yellow-400 before:rounded-l-full'
        : 'relative inline-flex items-center p-3 text-sm font-medium rounded-xl transition-all overflow-hidden text-gray-700 hover:bg-teal-50 hover:text-teal-700 before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-8 before:bg-yellow-400 before:rounded-l-full before:opacity-0 hover:before:opacity-100',
);
</script>

<template>
    <router-link :to="href" :class="classes">
        <slot />
    </router-link>
</template>