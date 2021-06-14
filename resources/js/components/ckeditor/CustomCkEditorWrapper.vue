<template>
    <div class="ck ck-content custom-ck-editor-wrapper">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'CustomCkEditorWrapper',
};
</script>

<style lang="scss">
.custom-ck-editor-wrapper {
    h2 {
        @apply text-4xl;
    }

    h3 {
        @apply text-3xl;
    }

    h4 {
        @apply text-2xl;
    }

    hr {
        height: 2px;
    }

    ul, ol {
        margin: 10px 0;
        padding-left: 40px;
    }

    ul {
        list-style: disc !important;
    }

    ol {
        list-style: decimal !important;
    }
}
</style>
