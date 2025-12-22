<template>
    <ConfirmationModal :show="state.show">
        <template #title>
            {{state.title}}
        </template>
        <template #content>
            {{state.message}}
        </template>
        <template #footer>
            <SecondaryButton ref="cancelButtonRef" @click="cancel" class="btn btn-secondary">Cancel</SecondaryButton>
            <PrimaryButton ref="confirmButtonRef" @click="confirm" class="btn btn-danger">Confirm</PrimaryButton>
        </template>
    </ConfirmationModal>
</template>

<script setup>
import { useConfirm } from '@/utilities/Composables/useConfirm';
import ConfirmationModal from './ConfirmationModal.vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import { watchEffect } from 'vue';
import { nextTick } from 'vue';
import { ref } from 'vue';

const {state, confirm, cancel} = useConfirm();
const cancelButtonRef = ref(null);
const confirmButtonRef = ref(null);

watchEffect(async () => {
    if (state.show){
        await nextTick();
        cancelButtonRef.value?.$el.focus();
    }
})

</script>