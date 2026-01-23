<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    'href':{
        required:true,
    },
    'filled':{
        default:false,
    }
});

</script>

<template>
    <Link :href="href" 
    :class="{'bg-indigo-500 text-indigo-50': filled}"
    class="whitespace-nowrap rounded-full py-0.5 px-2 border border-pink-500 text-pink-500 hover:bg-indigo-500 hover:text-indigo-50">
        <slot></slot>
    </Link>
</template>