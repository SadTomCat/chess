<template>
    <div class="admin-chess-rules">

        <!-- Select category  -->
        <div class="admin-chess-rules__select-category-block">
            <label for="rule-categories">Rule categories</label>
            <select class="admin-chess-rules__select-category"
                    id="rule-categories"
                    v-model="selectedCategory"
                    @change="categoryIsSelectedHandler"
            >
                <option v-for="category in ruleCategories" :key="category.id + category.name" :value="category.name">
                    {{ category.name }}
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
                              @delete="deleteRule"
            ></custom-ck-editor>
        </div>

        <!-- Bottom -->
        <div class="admin-chess-rules__bottom">
            <button class="admin-chess-rules__send-btn" @click="sendArticle">send</button>
        </div>

    </div>
</template>

<script>
import { onBeforeMount, ref } from 'vue';
import CustomCkEditor from '../../components/ckeditor/CustomCkEditor.vue';
import ruleCategoriesAllRequest from '../../api/ruleCategories/ruleCategoriesAllRequest';
import getRuleRequest from '../../api/rules/getRuleRequest';
import updateRuleRequest from '../../api/rules/updateRuleRequest';
import storeRuleRequest from '../../api/rules/storeRuleRequest';
import deleteRuleRequest from '../../api/rules/deleteRuleRequest';
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

        /* ---------- Editor ---------- */

        const ruleCategories = ref({});
        const selectedCategory = ref('');
        const editorData = ref('');
        const isUpdate = ref(false);

        const categoryIsSelectedHandler = async () => {
            setDefaultInformation();

            const category = ruleCategories.value.find((el) => el.name === selectedCategory.value);

            if (category === undefined) {
                return;
            }

            const res = await getRuleRequest(category.name);

            if (res.exists === false) {
                isUpdate.value = false;
                editorData.value = '';
                return;
            }

            isUpdate.value = true;
            editorData.value = res.content;
        };

        const validateContent = () => {
            if (editorData.value.length < 30) {
                setNotice('Minimum length 30 characters!');
                return false;
            }

            return true;
        };

        const storeArticle = async () => {
            const isValid = validateContent();

            if (isValid === false) {
                return;
            }

            const res = await storeRuleRequest(editorData.value, selectedCategory.value);

            if (res.status === false) {
                setError(res.message);
                return;
            }

            setSuccessful(`${selectedCategory.value} article was saved`);
            isUpdate.value = true;
        };

        const updateArticle = async () => {
            const isValid = validateContent();

            if (isValid === false) {
                return;
            }

            const res = await updateRuleRequest(editorData.value, selectedCategory.value);

            if (res.status === false) {
                setError(res.message);
                return;
            }

            setSuccessful(`${selectedCategory.value} article was updated`);
        };

        const sendArticle = async () => {
            setTimeout(async () => {
                if (selectedCategory.value === '') {
                    setNotice('You selected nothing');
                    return;
                }

                const runRequestHandler = isUpdate.value === true ? updateArticle : storeArticle;
                await runRequestHandler();
            }, 500);
        };

        const deleteRule = async () => {
            setDefaultInformation();

            if (selectedCategory.value === '') {
                setNotice('You selected nothing');
                return;
            }

            const res = await deleteRuleRequest(selectedCategory.value);

            if (res.status === false) {
                setError('Content is not deleted.');
                return;
            }

            editorData.value = '';
            selectedCategory.value = '';
            setSuccessful('Content is deleted.');
        };

        onBeforeMount(async () => {
            const ruleCategoriesReq = await ruleCategoriesAllRequest();
            ruleCategories.value = ruleCategoriesReq.categories;
        });

        return {
            ruleCategories,
            selectedCategory,
            editorData,
            sendArticle,
            categoryIsSelectedHandler,
            deleteRule,
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

    &__select-category-block {
        @apply mb-10;

        label {
            @apply block pl-2 mb-1;
        }
    }

    &__select-category {
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
