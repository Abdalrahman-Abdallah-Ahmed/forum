<script setup>
    import { onMounted, ref } from 'vue';
    
    defineProps({
        modelValue: String,
    });
    
    defineEmits(['update:modelValue']);
    
    const input = ref(null);
    
    onMounted(() => {
        if (input.value.hasAttribute('autofocus')) {
            input.value.focus();
        }
    });
    
    defineExpose({ focus: () => input.value.focus() });
    </script>
    
    <template>
        <textarea
            ref="input"
            class="block w-full rounded-md bg-white/5 px-3.5 py-2 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        >
        </textarea>
    </template>
    