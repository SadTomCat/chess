<template>
    <div class="base-table-search">
        <span class="base-table-search__search-icon material-icons">search</span>

        <input class="base-table-search__input"
               type="text"
               placeholder="search"
               v-model.trim="searchText"
               @keyup.enter="searchAction"
               @focusout="searchAction"
        >
    </div>
</template>

<script>
import { onBeforeMount, onBeforeUnmount, ref } from 'vue';
import { useStore } from 'vuex';
import stringHelper from '~/helpers/stringHelper';

export default {
    name: 'SearchInTable',

    props: {
        columns: {
            type: Array,
            default: () => ([]),
        },
    },

    setup(props, { emit }) {
        const store = useStore();
        const { upperFirstLetter } = stringHelper();

        const searchText = ref('');

        let beforeSearchNeedle = '';
        let beforeSearchColumns = [];

        const areSearchColumnsChanged = () => {
            if (beforeSearchColumns.length !== store.state.searchInTable.selectedSearchColumns.length) {
                return true;
            }

            let status = false;
            store.state.searchInTable.selectedSearchColumns.forEach((el) => {
                if (beforeSearchColumns.includes(el) === false) {
                    status = true;
                }
            });

            return status;
        };

        const searchAction = () => {
            if (store.state.searchInTable.selectedSearchColumns.length === 0) {
                console.log('Zero search columns');
                return;
            }

            if (beforeSearchNeedle === searchText.value && areSearchColumnsChanged() === false) {
                console.log('Already searched. Try changing search columns or search text.');
                return;
            }

            beforeSearchNeedle = searchText.value;

            if (searchText.value.length === 0) {
                beforeSearchNeedle = '';
                emit('searchAction', '');
                return;
            }

            if (searchText.value.length < 3) {
                console.log('too small string in search');
                return;
            }

            beforeSearchColumns = store.state.searchInTable.selectedSearchColumns;
            emit('searchAction', searchText.value, store.state.searchInTable.selectedSearchColumns);
        };

        onBeforeMount(() => {
            store.commit('SET_SELECTED_COLUMNS', props.columns);
        });

        onBeforeUnmount(() => {
            store.commit('SET_SELECTED_COLUMNS', []);
        });

        return {
            upperFirstLetter,
            searchText,
            searchAction,
            selectedSearchColumns: store.state.selectedSearchColumns,
        };
    },
};
</script>

<style lang="scss">
.base-table-search {
    @apply flex justify-between shadow w-full px-5 bg-white rounded-xl;

    &__search-icon {
        @apply pt-2 text-3xl text-gray-500;
    }

    &__input {
        @apply w-full bg-none bg-opacity-100 text-2xl border-none focus:outline-none focus:ring-0;
    }
}
</style>
