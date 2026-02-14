<script setup lang="ts">
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { relativeDate } from '@/utilities/date';
import PageHeading from '@/Components/PageHeading.vue';
import Bills from '@/Components/Bills.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps(['posts','topics', 'selectedTopic', 'query']);

const formattedDate = (post) => {return relativeDate(post.created_at);}
const searchForm = useForm({
    query:props.query,

});

const search = () => searchForm.get(route('posts.index'))

</script>

<template>
    <AppLayout>
        <Container>
            <div>
                <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All Posts'"/>
                <p v-if="selectedTopic" class="mt-1 text-gray-600 text-sm">{{ selectedTopic.description }}</p>
                <menu class="flex space-x-1 mt-3 overflow-x-auto pb-2 pt-1">
                    <li><Bills
                        :filled="!selectedTopic"  
                        :href="route('posts.index')">All Posts</Bills></li>
                    <li v-for="topic in topics" :key="topic.id">
                    <Bills
                    :filled="topic.id === selectedTopic?.id" 
                    :href="route('posts.index', {topic: topic.slug})">
                        {{ topic.name }}
                    </Bills>
                    </li>
                </menu>
                <form @submit.prevent="search" class="mt-4">
                    <div>
                        <InputLabel for="query">Search</InputLabel>
                        <div class="flex space-x-2 mt-1">
                            <TextInput v-model="searchForm.query" id="query" class="w-full"></TextInput>
                            <SecondaryButton type="submit">Search</SecondaryButton>
                        </div>
                    </div>
                </form>
            </div>
            <ul class="divide-y mt-4">
                <li v-for="post in posts.data" :key="post.id" class="flex justify-between items-baseline flex-col md:flex-row">
                    <Link :href="post.routes.show" class="group px-2 py-4 block">
                        <span class="font-bold text-lg group-hover:text-indigo-500">{{ post.title }}</span>
                        <span class="block mt-1 text-sm text-gray-600 ">{{ formattedDate(post) }} by {{
                            post.user.name}}</span>
                    </Link>
                    <Bills 
                    :href="route('posts.index', {topic: post.topic.slug})">
                        {{ post.topic.name }}
                    </Bills>
                </li>
            </ul>
            <Pagination :meta="posts.meta" />
        </Container>
    </AppLayout>
</template>