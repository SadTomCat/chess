<template>
    <base-table-settings-card :title="'Search columns'">
        <ul class="table-search-settings-card__list">

            <li v-for="column in columns" :key="column">
                <input type="checkbox"
                       :id="`search-${column}`"
                       :value="column"
                       v-model="store.state.searchInTable.selectedSearchColumns"
                >

                <label :for="`search-${column}`">
                    {{ column }}
                </label>
            </li>

        </ul>
    </base-table-settings-card>
</template>

<script>
import { useStore } from 'vuex';
import BaseTableSettingsCard from './BaseTableSettingsCard.vue';

export default {
    name: 'TableSearchSettingsCard',

    props: {
        columns: {
            type: Array,
            default: () => ([]),
        },
    },

    setup() {
        const store = useStore();

        return {
            store,
        };
    },

    components: { BaseTableSettingsCard },
};
</script>

<style lang="scss">
.table-search-settings-card {
    &__list {
        @apply mt-4 pb-3 text-xl;

        li {
            @apply space-x-3;
        }
    }
}
</style>
