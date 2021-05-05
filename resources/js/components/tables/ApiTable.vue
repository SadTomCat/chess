<template>
    <base-table :columns="columns"
                :items="items"
                :default-actions="defaultActions"
                :need-add-action="needAddAction"
                :per-page="currentPerPage"
                :current-page="currentPage"
                :total-pages="totalPages"
                @newPageAction="newPageAction"
                @viewAction="viewAction"
                @editAction="editAction"
                @deleteAction="deleteAction"
                @newPerPageAction="newPerPageAction"
    ></base-table>
</template>

<script>
import { onBeforeMount, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import BaseTable from './BaseTable.vue';
import getFromTableRequest from '../../api/getFromTableRequest';

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

        const items = reactive([]);

        const newPageAction = async (newPage) => {
            const res = await getFromTableRequest(props.table, props.columns, newPage, currentPerPage.value);

            if (res.status === false) {
                console.log(`Cannot get items from ${props.table}`);
                return;
            }

            totalPages.value = res.last_page;
            totalItems.value = res.total_items;

            items.length = 0;
            currentPage.value = newPage;
            res.items.forEach((el) => {
                items.push(el);
            });
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

        onBeforeMount(async () => {
            await newPageAction(1);
        });

        return {
            currentPage,
            totalPages,
            items,
            currentPerPage,
            newPageAction,
            viewAction,
            editAction,
            deleteAction,
            newPerPageAction,
        };
    },

    components: { BaseTable },
};
</script>

<style lang="scss">

</style>
