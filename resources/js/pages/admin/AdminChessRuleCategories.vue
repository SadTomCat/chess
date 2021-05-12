<template>
    <div class="admin-chess-rule-categories px-10 py-10">
        <base-list :title="'Rules categories'"
                   :items="normalizedCategories"
                   :edit-button="true"
                   :delete-button="true"
                   :add-item-button="true"
                   @editAction="editAction"
                   @deleteAction="deleteAction"
                   @addItemAction="addItemAction"
        ></base-list>

        <base-pop-up @closeAction="closePopUp" v-if="needPopUp === true" :height="'20rem'">

            <div class="admin-chess-rule-categories-edit">
                <h1>Editing rule category</h1>

                <base-input-wrapper>
                    <label for="edit-rule-category">Edit</label>
                    <input id="edit-rule-category" type="text" v-model="editing">
                </base-input-wrapper>

                <div class="admin-chess-rule-categories-edit__btn-menu">
                    <button class="bg-red-500" @click="backEditHandler">cancel</button>
                    <button class="bg-green-600" @click="editCategoryHandler">edit</button>
                </div>

            </div>

        </base-pop-up>
    </div>
</template>

<script>
import { onBeforeMount, reactive, ref } from 'vue';
import BaseList from '../../components/lists/BaseList.vue';
import BasePopUp from '../../components/popUps/BasePopUp.vue';
import BaseInputWrapper from '../../components/inputs/BaseInputWrapper.vue';
import ruleCategoriesCreateRequest from '../../api/ruleCategories/ruleCategoriesCreateRequest';
import ruleCategoriesDeleteRequest from '../../api/ruleCategories/ruleCategoriesDeleteRequest';
import ruleCategoriesUpdateRequest from '../../api/ruleCategories/ruleCategoriesUpdateRequest';
import ruleCategoriesAllRequest from '../../api/ruleCategories/ruleCategoriesAllRequest';

export default {
    name: 'AdminChessRuleCategories',

    setup() {
        let categories = [];
        const normalizedCategories = reactive([]);
        const needPopUp = ref(false);
        let editingIndex = 0;
        const editing = ref('');
        let awaiting = false;

        const editAction = async (index) => {
            needPopUp.value = true;
            editingIndex = index;
            editing.value = categories[index].name;
        };

        const deleteAction = async (index) => {
            if (awaiting === true) {
                return;
            }

            awaiting = true;
            const { id } = categories[index];
            const data = await ruleCategoriesDeleteRequest(id);

            if (data.status === false) {
                console.log(data.message);
                awaiting = false;
                return;
            }

            categories = categories.filter((val, ind) => index !== ind);
            normalizedCategories.length = 0;

            categories.forEach((el) => {
                normalizedCategories.push(el.name);
            });
            awaiting = false;
        };

        const addItemAction = async (newItem) => {
            if (awaiting === true) {
                return;
            }

            awaiting = true;
            const data = await ruleCategoriesCreateRequest(newItem);

            if (data.status === false) {
                console.log(data.message);
                awaiting = false;
                return;
            }

            categories.push({
                id: data.id,
                name: newItem,
            });
            normalizedCategories.push(newItem);
            awaiting = false;
        };

        /* Editing pop up */
        const closePopUp = () => {
            editingIndex = 0;
            editing.value = '';
            needPopUp.value = false;
        };

        const backEditHandler = () => {
            editing.value = categories[editingIndex].name;
        };

        const editCategoryHandler = async () => {
            if (awaiting === true) {
                return;
            }

            awaiting = true;
            const { id } = categories[editingIndex];
            const data = ruleCategoriesUpdateRequest(id, editing.value);

            if (data.status === false) {
                console.log(data.message);
                awaiting = false;
                return;
            }

            categories[editingIndex].name = editing.value;
            normalizedCategories[editingIndex] = editing.value;
            closePopUp();
            awaiting = false;
        };

        onBeforeMount(async () => {
            const data = await ruleCategoriesAllRequest();

            if (data.status === false) {
                console.log(data.message);
                return;
            }

            data.categories.forEach((el) => {
                categories.push(el);
                normalizedCategories.push(el.name);
            });
        });

        return {
            normalizedCategories,
            editing,
            needPopUp,
            editAction,
            deleteAction,
            addItemAction,
            closePopUp,
            backEditHandler,
            editCategoryHandler,
        };
    },

    components: {
        BaseList,
        BasePopUp,
        BaseInputWrapper,
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
