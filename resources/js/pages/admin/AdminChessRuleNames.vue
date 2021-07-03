<template>
    <div class="admin-chess-rule-names">

        <base-informer-block :successful="information.successful"
                             :notice="information.notice"
                             :error="information.error"
        ></base-informer-block>

        <base-list :title="'Rule names'"
                   :items="ruleNames"
                   :edit-button="true"
                   :delete-button="true"
                   :add-item-button="true"
                   @editAction="editRuleNameHandler"
                   @deleteAction="deleteRuleNameHandler"
                   @addItemAction="storeRuleNameHandler"
        ></base-list>
    </div>
</template>

<script>
import { computed, onBeforeMount, ref } from 'vue';
import BaseList from '../../components/lists/BaseList.vue';
import BaseInformerBlock from '../../components/informers/BaseInformerBlock.vue';
import chessRuleNamesCreateRequest from '../../api/chessRuleNames/chessRuleNamesCreateRequest';
import chessRuleNamesDeleteRequest from '../../api/chessRuleNames/chessRuleNamesDeleteRequest';
import chessRuleNamesUpdateRequest from '../../api/chessRuleNames/chessRuleNamesUpdateRequest';
import chessRuleNamesAllRequest from '../../api/chessRuleNames/chessRuleNamesAllRequest';
import baseInformerHelper from '../../helpers/baseInformerHelper';

export default {
    name: 'AdminChessRuleNames',

    setup() {
        const ruleNamesInfo = ref([]);
        const ruleNames = computed(() => ruleNamesInfo.value.map((ruleNameInfo) => ruleNameInfo.name));
        let isAwaiting = false;

        const findRuleNameInfoIndex = (name) => ruleNamesInfo.value.findIndex((ruleName) => ruleName.name === name);

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

        const storeRuleNameHandler = async (newItem) => {
            const handler = async () => {
                const res = await chessRuleNamesCreateRequest(newItem);

                if (res.status === false) {
                    setError(res.message);
                    return;
                }

                ruleNamesInfo.value.push({
                    id: res.id,
                    name: newItem,
                });
            };

            await handlerRunner(handler);
        };

        const editRuleNameHandler = async (index, newValue) => {
            const handler = async () => {
                const ruleNameIndex = findRuleNameInfoIndex(ruleNames.value[index]);

                if (ruleNameIndex === -1) {
                    setError('Something went wrong');
                    return;
                }

                const { id } = ruleNamesInfo.value[ruleNameIndex];
                const res = await chessRuleNamesUpdateRequest(id, newValue);

                if (res.status === false) {
                    setError(res.message);
                    return;
                }

                ruleNamesInfo.value[ruleNameIndex].name = newValue;
            };

            await handlerRunner(handler);
        };

        const deleteRuleNameHandler = async (index) => {
            const handler = async () => {
                const ruleNameIndex = findRuleNameInfoIndex(ruleNames.value[index]);

                if (ruleNameIndex === -1) {
                    setError('Something went wrong');
                    return;
                }

                const { id } = ruleNamesInfo.value[ruleNameIndex];
                const res = await chessRuleNamesDeleteRequest(id);

                if (res.status === false) {
                    setError(res.message);
                    return;
                }

                ruleNamesInfo.value.splice(ruleNameIndex, 1);
            };

            await handlerRunner(handler);
        };

        /* ---------- Hooks ---------- */

        onBeforeMount(async () => {
            const res = await chessRuleNamesAllRequest();

            if (res.status === false) {
                setError(res.message);
                return;
            }

            ruleNamesInfo.value = res.names_info;
        });

        return {
            information,
            ruleNames,
            handlerRunner,
            deleteRuleNameHandler,
            storeRuleNameHandler,
            editRuleNameHandler,
        };
    },

    components: {
        BaseList,
        BaseInformerBlock,
    },
};
</script>

<style lang="scss">
.admin-chess-rule-names {
    @apply flex flex-col justify-between h-full px-10 py-10;

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
