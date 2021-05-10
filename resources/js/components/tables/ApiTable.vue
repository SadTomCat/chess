<template>
    <base-table :columns="columns"
                :items="items"
                :default-actions="defaultActions"
                :need-add-action="needAddAction"
                :per-page="currentPerPage"
                :current-page="currentPage"
                :total-pages="totalPages"
                :need-search="true"
                @newPageAction="newPageAction"
                @viewAction="viewAction"
                @editAction="editAction"
                @deleteAction="deleteAction"
                @newPerPageAction="newPerPageAction"
                @sortByColumnAction="sortByColumnAction"
                @searchAction="searchAction"
    ></base-table>
</template>

<script>
import { onBeforeMount, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import BaseTable from './BaseTable.vue';
import getFromTableRequest from '~/api/paginatedTableByAdminRequest';
import searchInTableRequest from '../../api/searchInTableRequest';

export default {
    name: 'ApiTable',

    props: {
        table: {
            type: String,
            required: true,
        },
        columns: {
            type: Array,
            required: true,
        },
        perPage: {
            type: Number,
            default: () => 10,
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

    setup(props) {
        const router = useRouter();

        const currentPage = ref(1);
        const totalPages = ref(1);
        const totalItems = ref(0);
        const currentPerPage = ref(props.perPage);
        const ordering = {
            column: 'id',
            by: 'asc',
        };

        const items = reactive([]);

        const updateAfterLoad = (data) => {
            totalPages.value = data.last_page ?? 1;
            totalItems.value = data.total_items ?? 1;

            items.length = 0;
            currentPage.value = data.current_page ?? 1;
            data.items.forEach((el) => {
                items.push(el);
            });
        };

        const loadDataWithoutSearch = async (page) => {
            const data = await getFromTableRequest(props.table, props.columns, page, currentPerPage.value, ordering);

            if (data.status === false) {
                return;
            }

            updateAfterLoad(data);
        };

        /* Search */
        let lastNeedle = '';
        let lastSearchColumns = [];
        let isSearching = false;

        const searchAction = async (needle = '', searchColumns, page = 1) => {
            if (isSearching === true && needle === '') {
                isSearching = false;
                lastNeedle = '';
                lastSearchColumns = [];
                await loadDataWithoutSearch(1);
                return;
            }

            if (needle === '') {
                return;
            }

            lastNeedle = needle;
            lastSearchColumns = searchColumns;
            isSearching = true;

            const data = await searchInTableRequest(
                props.table,
                props.columns,
                page,
                currentPerPage.value,
                needle,
                searchColumns,
                ordering,
            );

            if (data.status === false) {
                return;
            }

            updateAfterLoad(data);
        };

        /* Actions */
        const newPageAction = async (page) => {
            if (isSearching === true) {
                await searchAction(lastNeedle, lastSearchColumns, page);
            } else {
                await loadDataWithoutSearch(page);
            }
        };

        const viewAction = (index) => {
            const formattedTableName = props.table.replace(/_/g, '-');

            router.push(`/admin/view/${formattedTableName}/${items[index].id}`);
        };

        const editAction = (index) => {
            console.log(`api edit, index: ${index}`);
        };

        const deleteAction = (index) => {
            console.log(`api delete, index: ${index}`);
        };

        const newPerPageAction = async (newPerPage) => {
            if (newPerPage === currentPage.value) {
                return;
            }

            const itemsPrinted = ((currentPage.value - 1) * currentPerPage.value) + items.length;
            const newCurrentPage = Math.floor(itemsPrinted / newPerPage);
            currentPage.value = newCurrentPage > 0 ? newCurrentPage : 1;
            currentPerPage.value = Number(newPerPage);
            await newPageAction(currentPage.value);
        };

        const sortByColumnAction = (column, type) => {
            ordering.by = type === 'firstMore' ? 'desc' : 'asc';
            ordering.column = column.toLowerCase().replace(/\s/g, '_');

            newPageAction(currentPage.value);
        };

        onBeforeMount(async () => {
            await loadDataWithoutSearch(1);
        });

        return {
            currentPage,
            totalPages,
            items,
            currentPerPage,
            loadDataWithoutSearch,
            searchAction,
            newPageAction,
            viewAction,
            editAction,
            deleteAction,
            newPerPageAction,
            sortByColumnAction,
        };
    },

    components: { BaseTable },
};
</script>

<style lang="scss">

</style>
