<template>
    <base-table :columns="columns"
                :items="items"
                :default-actions="defaultActions"
                :need-add-action="needAddAction"
                :per-page="perPage"
                :current-page="currentPage"
                :total-pages="totalPages"
                @newPageAction="newPageAction"
                @viewAction="viewAction"
                @editAction="editAction"
                @deleteAction="deleteAction"
    ></base-table>
</template>

<script>
import { onBeforeMount, reactive, ref } from 'vue';
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
        const currentPage = ref(1);
        const totalPages = ref(1);
        const totalItems = ref(0);

        const items = reactive([]);

        const newPageAction = async (newPage) => {
            const res = await getFromTableRequest(props.table, props.columns, newPage, props.perPage);

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
            console.log(`api view, index: ${index}`);
        };

        const editAction = (index) => {
            console.log(`api edit, index: ${index}`);
        };

        const deleteAction = (index) => {
            console.log(`api delete, index: ${index}`);
        };

        onBeforeMount(async () => {
            await newPageAction(1);
        });

        return {
            currentPage,
            totalPages,
            items,
            newPageAction,
            viewAction,
            editAction,
            deleteAction,
        };
    },

    components: { BaseTable },
};
</script>

<style lang="scss">

</style>
