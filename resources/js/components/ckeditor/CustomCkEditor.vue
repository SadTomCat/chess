<template>
    <custom-ck-editor-wrapper>
        <div class="custom-ck-editor__top">
            <span class="custom-ck-editor__preview-btn material-icons" @click="preview">visibility</span>

            <span class="custom-ck-editor__trash-btn material-icons" @click="$emit('delete')">delete_forever</span>
        </div>

        <ckeditor :editor="editor"
                  :model-value="modelValue"
                  @update:model-value="$emit('update:modelValue', $event);"
                  :config="editorConfig"
        ></ckeditor>
    </custom-ck-editor-wrapper>

    <teleport to="body">
        <custom-ck-editor-preview :makeup="modelValue"
                         @closeCKEditorPreview="closeCKEditorPreview"
                         v-if="showPreview === true"
        ></custom-ck-editor-preview>
    </teleport>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials';
import Autoformat from '@ckeditor/ckeditor5-autoformat/src/autoformat';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic';
import BlockQuote from '@ckeditor/ckeditor5-block-quote/src/blockquote';
import Underline from '@ckeditor/ckeditor5-basic-styles/src/underline';
import FontFamily from '@ckeditor/ckeditor5-font/src/fontfamily';
import FontSize from '@ckeditor/ckeditor5-font/src/fontsize';
import FontColor from '@ckeditor/ckeditor5-font/src/fontcolor';
import FontBackgroundColor from '@ckeditor/ckeditor5-font/src/fontbackgroundcolor';
import Heading from '@ckeditor/ckeditor5-heading/src/heading';
import Link from '@ckeditor/ckeditor5-link/src/link';
import List from '@ckeditor/ckeditor5-list/src/list';
import TodoList from '@ckeditor/ckeditor5-list/src/todolist';
import Image from '@ckeditor/ckeditor5-image/src/image';
import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar';
import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle';
import ImageUpload from '@ckeditor/ckeditor5-image/src/imageupload';
import ImageInsert from '@ckeditor/ckeditor5-image/src/imageinsert';
import AutoImage from '@ckeditor/ckeditor5-image/src/autoimage';
import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize';
import SimpleUploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';
import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
import HorizontalLine from '@ckeditor/ckeditor5-horizontal-line/src/horizontalline';
import Table from '@ckeditor/ckeditor5-table/src/table';
import TableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar';
import TableCellProperties from '@ckeditor/ckeditor5-table/src/tablecellproperties';
import SpecialCharacters from '@ckeditor/ckeditor5-special-characters/src/specialcharacters';
import SpecialCharactersEssentials from '@ckeditor/ckeditor5-special-characters/src/specialcharactersessentials';
import { ref } from 'vue';
import CustomCkEditorWrapper from './CustomCkEditorWrapper.vue';
import CustomCkEditorPreview from './CustomCkEditorPreview.vue';

function SpecialCharactersChessmen(editor) {
    editor.plugins.get('SpecialCharacters').addItems('Chessmen', [
        {
            title: 'king',
            character: '♚',
        },
        {
            title: 'queen',
            character: '♛',
        },
        {
            title: 'rook',
            character: '♜️',
        },
        {
            title: 'bishop',
            character: '♝',
        },
        {
            title: 'knight',
            character: '♞',
        },
        {
            title: 'pawn',
            character: '♟',
        },
    ]);
}

const editorConfig = {
    plugins: [
        Essentials,
        Autoformat,
        Bold,
        Italic,
        BlockQuote,
        FontFamily,
        FontSize,
        FontColor,
        FontBackgroundColor,
        Heading,
        Image,
        ImageToolbar,
        ImageStyle,
        ImageInsert,
        AutoImage,
        ImageResize,
        ImageUpload,
        SimpleUploadAdapter,
        Link,
        List,
        TodoList,
        Paragraph,
        Alignment,
        HorizontalLine,
        Underline,
        Table,
        TableToolbar,
        TableCellProperties,
        SpecialCharacters,
        SpecialCharactersChessmen,
        SpecialCharactersEssentials,
    ],
    toolbar: {
        items: [
            'heading',
            '|',
            'fontFamily',
            'fontSize',
            'fontColor',
            'fontBackgroundColor',
            '|',
            'alignment:left',
            'alignment:right',
            'alignment:center',
            'alignment:justify',
            '|',
            'bold',
            'italic',
            'underline',
            '|',
            'bulletedList',
            'numberedList',
            'todoList',
            '|',
            'imageInsert',
            '|',
            'insertTable',
            '|',
            'specialCharacters',
            'blockQuote',
            'horizontalLine',
            'link',
            '|',
            'undo',
            'redo',
        ],
    },
    fontSize: {
        options: [
            9,
            11,
            13,
            'default',
            17,
            19,
            21,
        ],
    },

    image: {
        toolbar: [
            'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight',
            '|',
            'resizeImage',
            '|',
            'imageTextAlternative',
        ],
        styles: [
            'alignLeft', 'alignCenter', 'alignRight',
        ],
    },

    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableCellProperties'],
    },

    simpleUpload: {
        uploadUrl: `${window.location.origin}/api/admin/images/ckeditor`,

        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    },
};

export default {
    name: 'CustomCkEditor',

    props: ['modelValue'],

    emits: ['update:modelValue', 'delete'],

    setup() {
        const showPreview = ref(false);

        const preview = () => {
            setTimeout(() => {
                showPreview.value = true;
            }, 500);
        };

        const closeCKEditorPreview = () => {
            showPreview.value = false;
        };

        return {
            editor: ClassicEditor,
            editorConfig,
            preview,
            showPreview,
            closeCKEditorPreview,
        };
    },

    components: {
        CustomCkEditorWrapper,
        CustomCkEditorPreview,
    },
};
</script>

<style lang="scss">
.custom-ck-editor__top {
    @apply flex justify-between items-center mb-4;
}

.custom-ck-editor__preview-btn {
    @apply text-4xl text-gray-800 cursor-pointer;
}

.custom-ck-editor__trash-btn {
    @apply text-4xl text-red-600 cursor-pointer;
}
</style>
