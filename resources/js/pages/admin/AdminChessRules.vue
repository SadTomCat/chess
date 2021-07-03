<template>
    <div class="admin-chess-rules">

        <!-- Select ruleName  -->
        <div class="admin-chess-rules__select-rule-name-block">
            <label for="rule-names">Rule Names</label>
            <select class="admin-chess-rules__select-rule-name"
                    id="rule-names"
                    v-model="selectedRuleName"
                    @change="ruleNameIsSelectedHandler"
            >
                <option v-for="ruleNameInfo in ruleNamesInfo" :key="ruleNameInfo.name" :value="ruleNameInfo.name">
                    {{ ruleNameInfo.name }}
                </option>
            </select>
        </div>

        <!-- Information  -->
        <div class="admin-chess-rules__action-information">

            <div class="admin-chess-rules-action-information__base admin-chess-rules-action-information__successful"
                 v-if="information.successful.length > 0"
            >
                <span class="material-icons">done</span>
                <p>{{ information.successful }}</p>
            </div>

            <div class="admin-chess-rules-action-information__base admin-chess-rules-action-information__notice"
                 v-if="information.notice.length > 0"
            >
                <span class="material-icons">info</span>
                <p>{{ information.notice }}</p>
            </div>

            <div class="admin-chess-rules-action-information__base admin-chess-rules-action-information__error"
                 v-if="information.error.length > 0"
            >
                <span class="material-icons">cancel</span>
                <p>{{ information.error }}</p>
            </div>

        </div>

        <!-- Editor -->
        <div class="admin-chess-rules__editor">
            <custom-ck-editor v-model="editorData"
                              @delete="deleteRuleContentHandler"
            ></custom-ck-editor>
        </div>

        <!-- Bottom -->
        <div class="admin-chess-rules__bottom">
            <button class="admin-chess-rules__send-btn" @click="sendRuleHandler">send</button>
        </div>

    </div>
</template>

<script>
import { onBeforeMount, ref } from 'vue';
import CustomCkEditor from '../../components/ckeditor/CustomCkEditor.vue';
import chessRuleNamesAllRequest from '../../api/chessRuleNames/chessRuleNamesAllRequest';
import chessRulesGetOneRequest from '../../api/chessRules/chessRulesGetOneRequest';
import chessRulesUpdateRequest from '../../api/chessRules/chessRulesUpdateRequest';
import chessRulesDeleteRequest from '../../api/chessRules/chessRulesDeleteRequest';
import baseInformerHelper from '../../helpers/baseInformerHelper';

export default {
    name: 'AdminChessRules',

    setup() {
        /* ---------- Informer ---------- */

        const {
            information,
            setDefaultInformation,
            setSuccessful,
            setNotice,
            setError,
        } = baseInformerHelper();

        /* ---------- Rule names ---------- */

        const ruleNamesInfo = ref([]);

        /** @return  ChessRuleNameInfo|undefined */
        const findRuleNameInfo = (name) => ruleNamesInfo.value.find((ruleNameInfo) => ruleNameInfo.name === name);

        /* ---------- Editor ---------- */
        const selectedRuleName = ref('');
        const editorData = ref('');

        const validateContent = () => {
            if (editorData.value.length < 30) {
                setNotice('Minimum length 30 characters!');
                return false;
            }

            return true;
        };

        const ruleNameIsSelectedHandler = async () => {
            setDefaultInformation();

            const ruleNameInfo = findRuleNameInfo(selectedRuleName.value);

            if (ruleNameInfo === undefined) return;

            const res = await chessRulesGetOneRequest(ruleNameInfo.slug);

            if (res.exists === false || res.status === false) {
                const message = res.exists === false ? '' : res.message;
                editorData.value = '';
                setError(message);
                return;
            }

            editorData.value = res.content;
        };

        const updateRuleRequest = async () => {
            const ruleNameInfo = findRuleNameInfo(selectedRuleName.value);

            if (ruleNameInfo === undefined) return;

            const res = await chessRulesUpdateRequest(editorData.value, ruleNameInfo.slug);

            if (res.status === false) {
                setError(res.message);
                return;
            }

            setSuccessful(`${selectedRuleName.value} rule was updated`);
        };

        const sendRuleHandler = async () => {
            setTimeout(async () => {
                if (selectedRuleName.value === '') {
                    setNotice('You selected nothing');
                    return;
                }

                const isValid = validateContent();

                if (isValid === false) return;

                await updateRuleRequest();
            }, 500);
        };

        const deleteRuleContentHandler = async () => {
            setDefaultInformation();

            if (selectedRuleName.value === '') {
                setNotice('You selected nothing');
                return;
            }

            const ruleNameInfo = findRuleNameInfo(selectedRuleName.value);

            if (ruleNameInfo === undefined) return;

            const res = await chessRulesDeleteRequest(ruleNameInfo.slug);

            if (res.status === false) {
                const message = res.message ?? 'Content is not deleted.';
                setError(message);
                return;
            }

            editorData.value = '';
            setSuccessful('Content is deleted.');
        };

        onBeforeMount(async () => {
            const data = await chessRuleNamesAllRequest();

            if (data.status === false || data.names_info.length === 0) {
                const message = data.names_info.length === 0 ? 'Rule names haven\'t been received' : data.message;
                setError(message);
                return;
            }

            ruleNamesInfo.value = data.names_info;
        });

        return {
            ruleNamesInfo,
            selectedRuleName,
            editorData,
            sendRuleHandler,
            ruleNameIsSelectedHandler,
            deleteRuleContentHandler,
            information,
        };
    },

    components: {
        CustomCkEditor,
    },
};
</script>

<style lang="scss">
.admin-chess-rules {
    @apply px-10 py-10;

    &__select-rule-name-block {
        @apply mb-10;

        label {
            @apply block pl-2 mb-1;
        }
    }

    &__select-rule-name {
        @apply w-2/3 rounded-full border-none outline-none ring-1 ring-gray-500 focus:ring-2 focus:ring-pink-500;
    }

    &__bottom {
        @apply flex justify-end mt-5;

        .admin-chess-rules__send-btn {
            @apply focus:outline-none px-9 py-3 bg-yellow-600 text-white rounded-full;
        }
    }
}

.admin-chess-rules-action-information {
    &__base {
        @apply flex justify-between items-center w-full h-14 rounded-xl text-white;
        @apply mb-6 px-5;

        p {
            @apply text-2xl w-full ml-4;
        }

        span {
            @apply text-3xl;
        }
    }

    &__successful {
        @apply bg-green-600;
    }

    &__notice {
        @apply bg-yellow-600;
    }

    &__error {
        @apply bg-red-600;
    }
}
</style>
