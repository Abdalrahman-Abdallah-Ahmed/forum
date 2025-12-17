<template>
    <ul role="list" class="divide-y divide-white/5">
        <li class="flex justify-between gap-x-6 py-5">
            <div class="flex min-w-0 gap-x-4">
                <img class="size-10 flex-none rounded-full bg-gray-800 outline -outline-offset-1 outline-white/10"
                    :src="comment.user.profile_photo_url" alt="" />
                <div class="min-w-0 flex-auto">
                    <p class="mt-1 truncate text-xs/5 text-gray-400">{{ comment.user.email }}</p>
                    <span class="text-sm">{{ comment.body }}</span>
                    <span class="block mt-1 text-xs text-gray-600 ">By {{ comment.user.name }} {{
                        relativeDate(comment.created_at) }}</span>
                    <div class="mt-1">
                        <form v-if="comment.can?.delete" @submit.prevent="$emit('delete', comment.id)" >
                            <button class="text-xs text-red-500">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</template>

<script setup>
import { relativeDate } from '@/utilities/date';
import { defineProps, defineEmits } from 'vue';

const props = defineProps(['comment']);

const emit = defineEmits(['delete']);

</script>