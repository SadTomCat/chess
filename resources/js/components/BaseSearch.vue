<template>
    <div class="base-search">
        <span class="base-search__search-icon material-icons">search</span>

        <input class="base-search__input"
               type="text"
               v-model.trim="searchText"
               :placeholder="placeholder"
               @keyup.enter="searchAction"
               @focusout="searchAction"
               @input="$emit('inputChange', searchText)"
        >
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name: 'BaseSearch',

    emits: ['searchAction', 'searchAction', 'inputChange'],

    props: {
        placeholder: {
            type: String,
            default: () => 'search',
        },
    },

    setup(props, { emit }) {
        let oldSearchText = '';
        const searchText = ref('');

        const searchAction = () => {
            if (oldSearchText === searchText.value) {
                return;
            }

            oldSearchText = searchText.value;
            emit('searchAction', searchText.value);
        };

        return {
            searchText,
            searchAction,
        };
    },
};
</script>

<style lang="scss">
.base-search {
    @apply flex justify-between shadow w-full px-5 bg-white rounded-xl;

    &__search-icon {
        @apply pt-2 text-3xl text-gray-500;
    }

    &__input {
        @apply w-full bg-none bg-opacity-100 text-2xl border-none focus:outline-none focus:ring-0;
    }
}
</style>
