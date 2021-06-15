<template>
    <div class="admin-chess-rule-categories px-10 py-10">

        <base-informer-block :successful="information.successful"
                             :notice="information.notice"
                             :error="information.error"
        ></base-informer-block>

        <base-list :title="'Rules categories'"
                   :items="normalizedCategories"
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
import { onBeforeMount, reactive } from 'vue';
import BaseList from '../../components/lists/BaseList.vue';
import BaseInformerBlock from '../../components/informers/BaseInformerBlock.vue';
import ruleCategoriesCreateRequest from '../../api/ruleCategories/ruleCategoriesCreateRequest';
import ruleCategoriesDeleteRequest from '../../api/ruleCategories/ruleCategoriesDeleteRequest';
import ruleCategoriesUpdateRequest from '../../api/ruleCategories/ruleCategoriesUpdateRequest';
import ruleCategoriesAllRequest from '../../api/ruleCategories/ruleCategoriesAllRequest';
import baseInformerHelper from '../../helpers/baseInformerHelper';

export default {
    name: 'AdminChessRuleCategories',

    setup() {
        let categories = [];
        const normalizedCategories = reactive([]);
        let isAwaiting = false;

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

        const deleteCategoryHandler = async (index) => {
            const handler = async () => {
                const { id } = categories[index];
                const res = await ruleCategoriesDeleteRequest(id);

                if (res.status === false) {
                    setError(res.message);
                    return;
                }

                categories = categories.filter((val, ind) => index !== ind);
                normalizedCategories.length = 0;

                categories.forEach((el) => {
                    normalizedCategories.push(el.name);
                });
            };

            await handlerRunner(handler);
        };

        const storeCategoryHandler = async (newItem) => {
            const handler = async () => {
                const res = await ruleCategoriesCreateRequest(newItem);

                if (res.status === false) {
                    setError(res.message);
                    return;
                }

                categories.push({
                    id: res.id,
                    name: newItem,
                });
                normalizedCategories.push(newItem);
            };

            await handlerRunner(handler);
        };

        const editCategoryHandler = async (index, newValue) => {
            const handler = async () => {
                const { id } = categories[index];
                const res = await ruleCategoriesUpdateRequest(id, newValue);

                if (res.status === false) {
                    setError(res.message);
                    return;
                }

                categories[index].name = newValue;
                normalizedCategories[index] = newValue;
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

            res.categories.forEach((el) => {
                categories.push(el);
                normalizedCategories.push(el.name);
            });
        });

        return {
            information,
            normalizedCategories,
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
