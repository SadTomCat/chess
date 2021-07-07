<template>
    <div class="base-list-wrapper" @keypress.enter="enterClicked">

        <div class="base-list">
            <h1>{{ title }}</h1>

            <ul class="base-list__list" v-if="showList">
                <li class="base-list__item" v-for="(item, index) in items" :key="item">
                    <div class="base-list-item__left">
                        <span class="base-list-item-left__index">{{ index + 1 }}.</span>

                        <input type="text"
                               class="base-list-item-left__input"
                               :value="item"
                               @input="newEditingValue = $event.target.value"
                               :disabled="editingIndex !== index"
                        >
                    </div>

                    <div class="base-list__actions">

                        <button class="base-list__button" @click="$emit('moreInfoAction', index)" v-if="moreInfoButton">
                            <span class="material-icons text-green-600">outbound</span>
                        </button>

                        <div v-if="editButton === true">
                            <div class="flex space-x-4" v-if="editingIndex === index">
                                <button class="base-list__button" @click="editingAction">
                                    <span class="material-icons text-green-500">done</span>
                                </button>

                                <button class="base-list__button" @click="closeEditing">
                                    <span class="material-icons text-red-500">close</span>
                                </button>
                            </div>

                            <button class="base-list__button" @click="startEditing(index)" v-else>
                                <span class="material-icons text-yellow-500">edit</span>
                            </button>
                        </div>

                        <button class="base-list__button" @click="$emit('deleteAction', index)" v-if="deleteButton">
                            <span class="material-icons text-red-500">delete</span>
                        </button>

                    </div>
                </li>

                <li class="base-list__item_new" v-if="showNewItem === true">
                    <input type="text" v-model="newItem">
                    <span class="material-icons text-green-600 ml-3" @click="addNewItemAction">done</span>
                    <span class="material-icons text-red-600 ml-2" @click="closeAddItemBlockHandler">close</span>
                </li>
            </ul>

            <!-- Empty list -->
            <div class="base-list__empty-list" v-else>
                <h2>Items not exist</h2>
            </div>
        </div>

        <div class="base-list__bottom">
            <div class="base-list__pagination-wrapper">
                <base-pagination :current-page="1"
                                 :total-pages="10"
                                 v-if="pagination"
                ></base-pagination>
            </div>

            <button class="base-list__add-item" @click="showAddItemHandler" v-if="addItemButton === true">
                <span class="select-none">+</span>
            </button>
        </div>
    </div>
</template>

<script>
import { computed, ref } from 'vue';
import BasePagination from '../BasePagination.vue';

// TODO: finish pagination
export default {
    name: 'BaseList',

    emits: ['moreInfoAction', 'editAction', 'deleteAction', 'addItemAction'],

    props: {
        title: String,

        items: Array,

        moreInfoButton: {
            type: Boolean,
            default: () => false,
        },

        editButton: {
            type: Boolean,
            default: () => false,
        },

        deleteButton: {
            type: Boolean,
            default: () => false,
        },

        addItemButton: {
            type: Boolean,
            default: () => false,
        },

        pagination: {
            type: Boolean,
            default: () => false,
        },
    },

    setup(props, { emit }) {
        const showNewItem = ref(false);
        const newItem = ref('');

        const showList = computed(() => props.items.length > 0 || showNewItem.value === true);

        const showAddItemHandler = () => {
            closeEditing();
            showNewItem.value = true;
        };

        const closeAddItemBlockHandler = () => {
            newItem.value = '';
            showNewItem.value = false;
        };

        const addNewItemAction = () => {
            if (showNewItem.value === false) return;

            emit('addItemAction', newItem.value);
            showNewItem.value = false;
            newItem.value = '';
        };

        /* Editing */
        const editingIndex = ref(-1);
        const newEditingValue = ref('');

        /**
         * No need for close current editing because :value in input reference on `props.items`
         * then after changing editingIndex, the current input returns the old value.
         * */
        const startEditing = (index) => {
            closeAddItemBlockHandler();
            newEditingValue.value = props.items[index];
            editingIndex.value = index;
        };

        const closeEditing = () => {
            newEditingValue.value = '';
            editingIndex.value = -1;
        };

        const editingAction = () => {
            if (editingIndex.value === -1 || newEditingValue.value.length === 0
                || newEditingValue.value === props.items[editingIndex.value]) {
                return;
            }

            emit('editAction', editingIndex.value, newEditingValue.value);
            closeEditing();
        };

        /* Handlers */
        const enterClicked = () => {
            addNewItemAction();
            editingAction();
        };

        return {
            showNewItem,
            newItem,
            showAddItemHandler,
            closeAddItemBlockHandler,
            addNewItemAction,
            showList,
            editingIndex,
            startEditing,
            closeEditing,
            newEditingValue,
            editingAction,
            enterClicked,
        };
    },

    components: {
        BasePagination,
    },
};
</script>

<style lang="scss">
.base-list {
    @apply pb-10 bg-white shadow rounded-2xl;

    h1 {
        @apply py-5 px-5 text-3xl;
    }

    &__list {
        @apply space-y-5 px-6 text-2xl;

    }

    &__item {
        @apply flex justify-between border-b-2;
    }

    .base-list-item__left {
        @apply flex items-center w-full;
    }

    .base-list-item-left__index {
        @apply mr-5 align-middle;
    }

    .base-list-item-left__input {
        @apply w-full focus:outline-none focus:ring-0 border-0;

        &, &::placeholder {
            @apply text-2xl text-black;
        }
    }

    &__actions {
        @apply flex space-x-3;

        button {
            @apply focus:outline-none;

            span {
                @apply text-4xl;
            }
        }
    }

    &__item_new {
        @apply flex justify-between items-center;

        input {
            @apply w-full text-2xl pl-0;
            @apply focus:outline-none focus:ring-0 focus:border-yellow-500 border-b-2;
        }

        span {
            @apply text-4xl mt-6 cursor-pointer;
        }
    }

    &__empty-list {
        @apply pl-5;

        h2 {
            @apply text-2xl text-gray-700;
        }
    }

    &__bottom {
        @apply flex justify-between mt-7 pr-2 w-full;
    }

    &__add-item {
        padding: 0.5rem 1.2rem;
        @apply bg-gradient-to-r from-yellow-500 to-pink-500 rounded-t-md;
        @apply rounded-full  text-white focus:outline-none;

        span {
            @apply text-5xl;
        }
    }

    input {
        border-top: 0;
        border-left: 0;
        border-right: 0;
    }
}
</style>
