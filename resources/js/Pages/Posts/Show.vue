<template>
    <AppLayout :title="post.title">
        <Container>
            <PageHeading>
                {{ post.title }}
            </PageHeading>
            <span class="block mt-1 text-sm text-gray-600 ">{{ formattedDate }} by {{ post.user.name }}</span>

            <article class="mt-6 prose prose-sm max-w-none" v-html="post.html">
            </article>
            <div class="mt-12">
                <h2 class="text-xl font-semibold">Comments</h2>
                <form v-if="$page.props.auth.user"
                    @submit.prevent="() => commentIdBeingEdited ? updateComment() : addComment()" class="mt-4">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <MarkdownEditor ref="commentTextAreaRef" id="body" v-model="commentForm.body"
                            placeholder="Speak your mind" editorClass="min-h-[160px]"/>
                        <InputError :message="commentForm.errors.body" class="mt-1" />
                    </div>
                    <PrimaryButton type="submit" class="mt-3" :disabled="commentForm.processing"
                        v-text="commentIdBeingEdited ? 'Update Comment' : 'Add Comment'">
                    </PrimaryButton>
                    <SecondaryButton v-if="commentIdBeingEdited" type="button" class="mt-3 ml-2"
                        :disabled="commentForm.processing" @click="cancelEditComment">
                        Cancel
                    </SecondaryButton>
                </form>
                <ul class="divide-y mt-4">
                    <li v-for="comment in comments.data" :key="comment.id" class=" px-2 py-4">
                        <Comment @edit="editComment" @delete="deleteComment" :comment="comment"></Comment>
                    </li>
                </ul>
                <Pagination :meta="comments.meta" :only="['comments']" />
            </div>
        </Container>

    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import Comment from '@/Components/Comment.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { relativeDate } from '@/utilities/date';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useConfirm } from '@/utilities/Composables/useConfirm';
import MarkdownEditor from '@/Components/MarkdownEditor.vue';
import PageHeading from '@/Components/PageHeading.vue';

const props = defineProps([
    'post',
    'comments'
]);

const formattedDate = computed(() => relativeDate(props.post.created_at));

const commentForm = useForm({
    body: '',
})

const commentTextAreaRef = ref(null);
const commentIdBeingEdited = ref(null);

const commentBeingEdited = computed(() => {
    return props.comments.data.find(comment => comment.id === commentIdBeingEdited.value);
});

const editComment = (commentID) => {
    commentIdBeingEdited.value = commentID;
    commentForm.body = commentBeingEdited.value?.body;
    commentTextAreaRef.value?.focus();
};

const cancelEditComment = () => {
    commentIdBeingEdited.value = null;
    commentForm.reset('body');
};

const { confirmation } = useConfirm();

const addComment = () => commentForm.post(route('posts.comments.store', props.post.id), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset('body'),
});

const updateComment = async () => {
    if (! await confirmation('Are you sure you want to update this comment?')) {
        commentTextAreaRef.value?.focus();
        return;
    }


    commentForm.put
    (
        route('comments.update',
            {
                comment: commentIdBeingEdited.value,
                page: props.comments.meta.current_page
            }),
        {
            preserveScroll: true,
            onSuccess: () => {
                commentForm.reset('body'),
                    commentIdBeingEdited.value = null;
            }
        }
    );
}

const deleteComment = async (commentID) => {
    if (! await confirmation('Are you sure you want to delete this comment?')) {
        return;
    }

    router.delete(route('comments.destroy', { comment: commentID, page: props.comments.meta.current_page }),
    {
        preserveScroll: true,
    }); 
};

</script>