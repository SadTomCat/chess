<template>
    <div class="admin-chess-rule-categories px-10 py-10">

        <base-informer-block :successful="information.successful"
                             :notice="information.notice"
                             :error="information.error"
        ></base-informer-block>

        <base-list :title="'Rules categories'"
                   :items="categoryNames"
                   :edit-button="true"
                   :delete-button="true"
                   :add-item-button="true"
                   @editAction="editCategoryHandler"
                   @deleteAction="deleteCategoryHandler"
                   @addItemAction="storeCategoryHandler"
        ></base-list>
    </div>
</template>

<script>
import { computed, onBeforeMount, ref } from 'vue';
import BaseList from '../../components/lists/BaseList.vue';
import BaseInformerBlock from '../../components/informers/BaseInformerBlock.vue';
import ruleCategoriesCreateRequest from '../../api/ruleCategories/ruleCategoriesCreateRequest';
import ruleCategoriesDeleteRequest from '../../api/ruleCategories/ruleCategoriesDeleteRequest';
import ruleCategoriesUpdateRequest from '../../api/ruleCategories/ruleCategoriesUpdateRequest';
import ruleCategoriesAllRequest from '../../api/ruleCategories/ruleCategoriesAllRequest';
import baseInformerHelper from '../../helpers/baseInformerHelper';

/**
 * All category names are unique.
 * */
export default {
    name: 'AdminChessRuleCategories',

    setup() {
        const categories = ref([]);
        const categoryNames = computed(() => categories.value.map((category) => category.name));
        let isAwaiting = false;

        const findCategoryIndexByName = (name) => categories.value.findIndex((category) => category.name === name);

        /* ---------- Informer ---------- */
        const {
            information,
            setDefaultInformation,
            setError,
        } = baseInformerHelper();

        /* ---------- Handlers ---------- */

        const handlerRunner = async (handler, ...args) => {
            if (window.isFunction(handler) === false) {
                return;
            }

            if (isAwaiting === true) {
                return;
            }

            setDefaultInformation();

            isAwaiting = true;
            await handler(...args);
            isAwaiting = false;
        };

        const storeCategoryHandler = async (newItem) => {
            const handler = async () => {
                const res = await ruleCategoriesCreateRequest(newItem);

                if (res.status === false) {
                    setError(res.message);
                    return;
                }

                categories.value.push({
                    id: res.id,
                    name: newItem,
                });
            };

            await handlerRunner(handler);
        };

        const editCategoryHandler = async (index, newValue) => {
            const handler = async () => {
                const categoryIndex = findCategoryIndexByName(categoryNames.value[index]);

                if (categoryIndex === -1) {
                    setError('Something went wrong');
                    return;
                }

                const { id } = categories.value[categoryIndex];
                const res = await ruleCategoriesUpdateRequest(id, newValue);

                if (res.status === false) {
                    setError(res.message);
                    return;
                }

                categories.value[categoryIndex].name = newValue;
            };

            await handlerRunner(handler);
        };

        const deleteCategoryHandler = async (index) => {
            const handler = async () => {
                const categoryIndex = findCategoryIndexByName(categoryNames.value[index]);

                if (categoryIndex === -1) {
                    setError('Something went wrong');
                    return;
                }

                const { id } = categories.value[categoryIndex];
                const res = await ruleCategoriesDeleteRequest(id);

                if (res.status === false) {
                    setError(res.message);
                    return;
                }

                categories.value.splice(categoryIndex, 1);
            };

            await handlerRunner(handler);
        };

        /* ---------- Hooks ---------- */

        onBeforeMount(async () => {
            const res = await ruleCategoriesAllRequest();

            if (res.status === false) {
                setError(res.message);
                return;
            }

            categories.value = res.categories;
        });

        return {
            information,
            categoryNames,
            handlerRunner,
            deleteCategoryHandler,
            storeCategoryHandler,
            editCategoryHandler,
        };
    },

    components: {
        BaseList,
        BaseInformerBlock,
    },
};
</script>

<style lang="scss">
.admin-chess-rule-categories-edit {
    @apply flex flex-col justify-between h-full px-10 pb-7;

    h1 {
        @apply pt-10 text-3xl;
    }

    &__btn-menu {
        @apply mt-7 self-end space-x-6 text-xl;

        button {
            @apply text-white px-4 py-2 rounded-full focus:outline-none;
        }
    }
}
</style>
