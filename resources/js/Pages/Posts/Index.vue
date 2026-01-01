<script setup lang="ts">
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { relativeDate } from '@/utilities/date';

defineProps(['posts']);

const formattedDate = (post) => {return relativeDate(post.created_at);}

</script>

<template>
    <AppLayout>
        <Container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id">
                    <Link :href="post.routes.show" class="group px-2 py-4 block">
                        <span class="font-bold text-lg group-hover:text-indigo-500">{{ post.title }}</span>
                        <span class="block mt-1 text-sm text-gray-600 ">{{ formattedDate(post) }} by {{
                            post.user.name}}</span>
                    </Link>
                </li>
            </ul>
            <Pagination :meta="posts.meta" />
        </Container>
    </AppLayout>
</template>