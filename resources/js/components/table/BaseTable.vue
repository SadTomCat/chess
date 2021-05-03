<template>
    <div class="base-table">
        <table v-if="maxCellWidth.maxWidth !== undefined">
            <!-- Head of table -->
            <thead>
            <tr>
                <td>№</td>
                <td :style="maxCellWidth" v-for="column in columns">
                    {{  upperFirstLetter(column.replace('/admin/', '').replace(/_/g, ' ')) }}
                </td>

                <td v-if="actions.length > 0">Actions</td>
            </tr>
            </thead>

            <!-- Main Part-->
            <tr v-for="index in countRow">

                <!-- Index -->
                <td>{{ index }}</td>

                <!-- From column -->
                <td :style="maxCellWidth" class="base-table__cell" v-for="column in columns">
                    {{ items[index - 1][column] ?? '—' }}
                </td>

                <!-- Actions -->
                <td class="base-table__actions" v-if="actions.length > 0">
                    <button v-for="action in actions"
                            :style="action.styles"
                            @click="action.action(index - 1)"
                    >
                        {{ action.name }}
                    </button>
                </td>
            </tr>

            <div v-if="items.length === 0" class="base-table__no-items">
                <h1>No items in this table</h1>
            </div>

        </table>

        <base-pagination :current-page="currentPage"
                         :total-pages="totalPages"
                         @newPageAction="newPageAction"
        ></base-pagination>
    </div>
</template>

<script>
import {
    computed, onBeforeUnmount, onMounted, reactive,
} from 'vue';
import BasePagination from '../BasePagination.vue';
import stringHelper from '~/helpers/stringHelper';

export default {
    name: 'BaseTable',

    props: {
        items: {
            type: Array,
            default: () => [],
        },
        columns: {
            type: Array,
            required: true,
        },
        perPage: {
            type: Number,
            default: () => 10,
        },
        currentPage: {
            type: Number,
            required: true,
        },
        totalPages: {
            type: Number,
            required: true,
        },
        defaultActions: {
            type: Array,
            default: () => ['view', 'edit', 'delete'],
        },
        needAddAction: {
            type: Boolean,
            default: () => false,
        },
    },

    setup(props, { emit }) {
        const { upperFirstLetter } = stringHelper();

        const defaultActions = {
            view: {
                action: (index) => {
                    emit('viewAction', index);
                },
            },
            edit: {
                action: (index) => {
                    emit('editAction', index);
                },
            },
            delete: {
                action: (index) => {
                    emit('deleteAction', index);
                },
            },
        };

        const actions = props.defaultActions.map((el) => ({
            name: el,
            action: defaultActions[el].action,
            styles: defaultActions[el].styles,
        }));

        const countRow = computed(() => (props.items.length > props.perPage
            ? props.perPage
            : props.items.length));

        const maxCellWidth = reactive({});

        const setMaxCellWidth = () => {
            const widthForCell = document.querySelector('.base-table').clientWidth - 296;
            maxCellWidth.maxWidth = `${widthForCell / (props.columns.length)}px`;
        };

        const newPageAction = (newPage) => {
            emit('newPageAction', newPage);
        };

        onMounted(() => {
            setMaxCellWidth();

            window.addEventListener('resize', setMaxCellWidth);
        });

        onBeforeUnmount(() => {
            window.removeEventListener('resize', setMaxCellWidth);
        });

        return {
            upperFirstLetter,
            countRow,
            actions,
            newPageAction,
            maxCellWidth,
        };
    },

    components: { BasePagination },
};
</script>

<style lang="scss">
.base-table {
    @apply w-full;

    table {
        @apply w-full rounded-xl bg-white shadow-lg mb-4;
    }

    thead {
        @apply text-white text-xl rounded-full;

        td:first-child {
            @apply rounded-tl-2xl w-1;
        }

        td:last-child {
            @apply rounded-tr-2xl;
        }

        td {
            @apply bg-pink-600 py-2;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    }

    td:not(td:last-child) {
        border-right: 2px solid #d0d0d0;
    }

    tr:not(tr:last-child) {
        border-bottom: 2px solid #d0d0d0;
    }

    td {
        @apply px-4 py-2;
    }

    &__cell {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &__actions {
        max-width: 240px;

        @apply flex justify-around flex-wrap;

        button {
            @apply flex py-1 px-3 mr-2 my-1 bg-gray-300 rounded-full outline-none;
        }

        button:hover {
            opacity: 50%;
        }
    }

    &__no-items {
        @apply w-full table-cell;

        h1 {
            @apply py-10 text-3xl text-gray-700 pl-4;
        }
    }

    //color: #F80F6CFF;
}
</style>
