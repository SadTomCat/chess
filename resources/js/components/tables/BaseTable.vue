<template>
    <div class="base-table">

        <!-- Top -->
        <div class="base-table__top">
            <search-in-table class="mb-5"
                             :columns="columns"
                             @searchAction="searchAction"
                             v-if="needSearch === true"
            ></search-in-table>

            <base-table-settings-panel>
                <table-search-settings-card :columns="columns" v-if="needSearch === true">
                </table-search-settings-card>

                <table-settings-card @newSettingsAction="newSettingsAction"></table-settings-card>
            </base-table-settings-panel>
        </div>

        <!-- Table block -->
        <div class="base-table__table-block" :class="[needShrinkColumns === false ? 'overflow-x-scroll' : '']">
            <table>
                <!-- Head of table -->
                <thead>
                <tr>
                    <td class="cursor-pointer" @click="defaultSortHandler">№</td>

                    <td :style="columnsStyles" v-for="column in columns" :key="column">

                        <div class="flex justify-between cursor-pointer" @click="sortByColumnAction(column)">
                            {{ upperFirstLetter(column.replace(/_/g, ' ')) }}

                            <div>
                                <span class="material-icons" v-if="firstMoreColumn === column">expand_less</span>
                                <span class="material-icons" v-else>expand_more</span>
                            </div>
                        </div>

                    </td>

                    <td v-if="actions.length > 0">Actions</td>
                </tr>
                </thead>

                <!-- Main Part-->
                <tr v-for="index in countRow" :key="index">

                    <!-- Index -->
                    <td>{{ index }}</td>

                    <!-- From column -->
                    <td class="base-table__cell" :style="columnsStyles" v-for="column in columns" :key="column">
                        {{ items[index - 1][column] ?? '—' }}
                    </td>

                    <!-- Actions -->
                    <td class="base-table__actions" v-if="actions.length > 0">

                        <button :style="action.styles"
                                @click="action.action(index - 1)"
                                v-for="action in actions"
                                :key="action"
                        >{{ action.name }}</button>

                    </td>
                </tr>

                <div class="base-table__no-items" v-if="items.length === 0">
                    <h1>No items in this table</h1>
                </div>

            </table>
        </div>

        <!-- Bottom -->
        <div class="base-table__bottom">
            <select @change="selectedPerPage" v-model="currentPerPage" class="base-table__select-page">
                <option>10</option>
                <option>15</option>
                <option>25</option>
            </select>

            <base-pagination :current-page="currentPage"
                             :total-pages="totalPages"
                             @newPageAction="newPageAction"
            ></base-pagination>
        </div>
    </div>
</template>

<script>
import {
    computed, onBeforeUnmount, onMounted, reactive, ref, watchEffect,
} from 'vue';
import BasePagination from '../BasePagination.vue';
import stringHelper from '~/helpers/stringHelper';
import SearchInTable from './BaseTableSearch.vue';
import BaseTableSettingsPanel from './BaseTableSettingsPanel.vue';
import TableSearchSettingsCard from './cards/TableSearchSettingsCard.vue';
import TableSettingsCard from './cards/TableSettingsCard.vue';

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
        defaultSortColumn: {
            type: Object,
            default: () => ({
                column: 'id',
                firstLess: true,
            }),
        },
        needSearch: {
            type: Boolean,
            default: () => false,
        },
        needAddAction: {
            type: Boolean,
            default: () => false,
        },
    },

    setup(props, { emit }) {
        const { upperFirstLetter } = stringHelper();

        /* Settings */
        const needShrinkColumns = ref(true);

        const newSettingsAction = (newSettings) => {
            const normalizedSettings = newSettings.map((el) => el.toLowerCase().replace(/\s/g, '_'));

            needShrinkColumns.value = normalizedSettings.includes('shrink_columns');
        };

        /* Setup */
        const countRow = computed(() => (props.items.length > props.perPage
            ? props.perPage
            : props.items.length));

        const maxCellWidth = reactive({});

        const columnsStyles = computed(() => ({
            maxWidth: needShrinkColumns.value === true ? maxCellWidth.maxWidth : '500px',
        }));

        const setMaxCellWidth = () => {
            const widthForCell = document.querySelector('.base-table').clientWidth - 296;
            maxCellWidth.maxWidth = `${widthForCell / (props.columns.length)}px`;
        };

        /* Actions */
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

        const newPageAction = (newPage) => {
            emit('newPageAction', newPage);
        };

        const firstMoreColumn = ref('');

        const defaultSortHandler = () => {
            let needleType;
            const { firstLess } = props.defaultSortColumn;
            const column = window.isString(props.defaultSortColumn.column) === true
                ? props.defaultSortColumn.column
                : 'id';

            if (window.isBoolean(firstLess) === false || firstLess === true) {
                needleType = 'firstLess';
                firstMoreColumn.value = '';
            } else {
                needleType = 'firstMore';
                firstMoreColumn.value = props.defaultSortColumn.column;
            }

            emit('sortByColumnAction', column, needleType);
        };

        const sortByColumnAction = (column) => {
            let needleType = '';

            if (firstMoreColumn.value !== column) {
                needleType = 'firstMore';
                firstMoreColumn.value = column;
            } else {
                needleType = 'firstLess';
                firstMoreColumn.value = '';
            }

            emit('sortByColumnAction', column, needleType);
        };

        const currentPerPage = ref(props.perPage);

        const selectedPerPage = () => {
            emit('newPerPageAction', currentPerPage.value);
            currentPerPage.value = props.perPage;
        };

        watchEffect(() => {
            currentPerPage.value = props.perPage;
        });

        /* Search Component */
        const searchAction = (needle, columns) => {
            emit('searchAction', needle, columns);
        };

        /* Hooks */
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
            needShrinkColumns,
            newSettingsAction,
            newPageAction,
            columnsStyles,
            currentPerPage,
            selectedPerPage,
            firstMoreColumn,
            sortByColumnAction,
            defaultSortHandler,
            searchAction,
        };
    },

    components: {
        BasePagination,
        BaseTableSettingsPanel,
        SearchInTable,
        TableSearchSettingsCard,
        TableSettingsCard,
    },
};
</script>

<style lang="scss">
.base-table {
    @apply w-full;

    &__top {
        @apply mb-7;
    }

    &__table-block {
        @apply mb-5;

        &::-webkit-scrollbar {
            @apply h-2 ;
        }

        &::-webkit-scrollbar-thumb {
            @apply rounded-full bg-gray-400;
        }
    }

    table {
        @apply w-full rounded-xl bg-white shadow;
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

    &__bottom {
        @apply flex justify-between;
    }

    &__select-page {
        @apply rounded-full border-none outline-none ring-1 focus:ring-2 ring-yellow-500 focus:ring-yellow-500;
    }

    //color: #F80F6CFF;
}
</style>
