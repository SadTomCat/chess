<template>
    <base-table-settings-card :title="'Settings'">
        <ul class="table-search-settings-card__list">

            <li v-for="setting in settings" :key="setting">
                <input type="checkbox"
                       :id="`setting-${setting}`"
                       :value="setting"
                       @change="newSettingsAction"
                       v-model="settingsSelected"
                >

                <label :for="`setting-${setting}`">{{ setting }}</label>
            </li>

        </ul>
    </base-table-settings-card>
</template>

<script>
import { onBeforeMount, ref } from 'vue';
import BaseTableSettingsCard from './BaseTableSettingsCard.vue';

export default {
    name: 'TableSettingsCard',

    setup(props, { emit }) {
        const settings = ['Shrink columns'];

        const settingsSelected = ref(settings);

        const newSettingsAction = () => {
            emit('newSettingsAction', settingsSelected.value);
        };

        onBeforeMount(() => {
            emit('newSettingsAction', settingsSelected.value);
        });

        return {
            settings,
            settingsSelected,
            newSettingsAction,
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
