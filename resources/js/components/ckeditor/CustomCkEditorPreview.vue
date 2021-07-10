<template>
    <custom-ck-editor-wrapper>
        <div class="custom-ck-editor-preview-wrapper">
            <div class="custom-ck-editor-preview" v-html="makeup">
            </div>

            <button class="custom-ck-editor-preview-close-btn" @click="$emit('closeCKEditorPreview')">X</button>
        </div>
    </custom-ck-editor-wrapper>
</template>

<script>
import CustomCkEditorWrapper from './CustomCkEditorWrapper.vue';

export default {
    name: 'CustomCkEditorPreview',

    props: {
        makeup: {
            type: String,
            required: true,
        },
    },

    emits: ['closeCKEditorPreview'],

    components: { CustomCkEditorWrapper },
};
</script>

<style lang="scss">
.custom-ck-editor-preview-wrapper {
    @apply py-24 bg-white;
    @apply flex justify-center fixed top-0 min-h-screen max-h-full w-full z-40 overflow-y-scroll;
}

.custom-ck-editor-preview {
    width: 80%;

    &:after {
        content: '';
        display: block;
        height: 3rem;
        width: 100%;
        padding-bottom: 3rem;
    }
}

.custom-ck-editor-preview-close-btn {
    @apply focus:outline-none;
    @apply absolute top-0 right-0 pt-8 pr-8 text-3xl text-red-500;
}
</style>
