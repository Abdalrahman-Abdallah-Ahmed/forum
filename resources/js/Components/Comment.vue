<template>
    <ul role="list" class="divide-y divide-white/5">
        <li class="flex justify-between gap-x-6 py-5">
            <div class="flex min-w-0 gap-x-4 w-200">
                <img class="size-10 flex-none rounded-full bg-gray-800 outline -outline-offset-1 outline-white/10"
                    :src="comment.user.profile_photo_url" alt="" />
                <div class="min-w-0 flex-auto">
                    <p class="mt-1 truncate text-xs/5 text-gray-400">{{ comment.user.email }}</p>
                    <div v-html="comment.html" class="mt-1 prose prose-sm max-w-none "></div>
                    <span class="block mt-1 text-xs text-gray-600 ">By {{ comment.user.name }} {{
                        relativeDate(comment.created_at) }}  | <span class="text-pink-500">{{ comment.likes_count }} Likes</span></span>
                    <div class="mt-1 flex justify-end space-x-3 empty:hidden">
                        <form v-if="comment.can?.update" @submit.prevent="$emit('edit', comment.id)" >
                            <button class="text-xs ">
                                Edit
                            </button>
                        </form>
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

const emit = defineEmits(['delete', 'edit']);


</script>