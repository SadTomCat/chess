<template>
    <base-table-settings-card>
        <div class="table-search-settings-card__header" @click="showSelectColumns = !showSelectColumns">
            <h1 class="">Search columns</h1>
            <span class="material-icons">expand_more</span>
        </div>

        <ul class="table-search-settings-card__list" v-if="showSelectColumns === true">

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
import { ref } from 'vue';
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

        const showSelectColumns = ref(false);

        return {
            store,
            showSelectColumns,
        };
    },

    components: { BaseTableSettingsCard },
};
</script>

<style lang="scss">
.table-search-settings-card {
    &__header {
        @apply flex justify-between cursor-pointer;

        h1 {
            @apply text-xl align-middle;
        }

        span {
            margin-top: 2px;
        }
    }

    &__list {
        @apply mt-4 pb-3 text-xl;

        li {
            @apply space-x-3;
        }
    }
}
</style>
