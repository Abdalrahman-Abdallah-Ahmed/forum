<template>
    <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6">

        <!-- ✅ Mobile view -->
        <div class="flex flex-1 justify-between sm:hidden">
            <Link :href="previousPage?.url || ''" :disabled="!previousPage?.url"
                class="relative inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium" :class="previousPage?.url
                    ? 'bg-white text-gray-700 hover:bg-gray-50'
                    : 'bg-gray-100 text-gray-400 cursor-not-allowed'" preserve-scroll>
                Previous
            </Link>

            <Link :href="nextPage?.url || ''" :disabled="!nextPage?.url"
                class="relative ml-3 inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium" :class="nextPage?.url
                    ? 'bg-white text-gray-700 hover:bg-gray-50'
                    : 'bg-gray-100 text-gray-400 cursor-not-allowed'" preserve-scroll>
                Next
            </Link>
        </div>

        <!-- ✅ Desktop view -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <p class="text-sm text-gray-700">
                Showing <span class="font-medium">{{ meta.from }}</span>
                to <span class="font-medium">{{ meta.to }}</span>
                of <span class="font-medium">{{ meta.total }}</span> results
            </p>

            <nav class="isolate inline-flex -space-x-px rounded-md shadow-xs bg-white">
                <Link v-for="link in meta.links" :key="link.label" :href="link.url || ''" :disabled="!link.url"
                    :only="only" class="relative inline-flex items-center px-3 py-2 text-sm border
            first-of-type:rounded-l-md last-of-type:rounded-r-md" :class="{
                'bg-indigo-600 text-white border-indigo-600': link.active,
                'text-gray-900 hover:bg-gray-50': link.url && !link.active,
                'text-gray-400 cursor-not-allowed bg-gray-100': !link.url
            }" v-html="link.label" preserve-scroll />
            </nav>
        </div>

    </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    meta: {
        type: Object,
        required: true
    },
    only: {
        type: Array,
        default: () => []
    }
})

const previousPage = computed(() =>
    props.meta.links.find(link => link.label.toLowerCase().includes('previous'))
)

const nextPage = computed(() =>
    props.meta.links.find(link => link.label.toLowerCase().includes('next'))
)
</script>