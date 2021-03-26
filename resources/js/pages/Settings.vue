<template>
    <div class="settings">
        <div class="settings-card">

            <!-- Header -->
            <div class="settings-card__header">
                <h1>Settings</h1>
            </div>

            <!-- Form block -->
            <div class="settings-card__form-block">

                <p class="settings-card__user-email">Email: {{ editedFields.email }}</p>

                <form>

                    <div class="settings-card__input-block">
                        <label for="name">Name</label>
                        <input type="text" id="name"
                               :disabled="disabled"
                               v-model="editedFields.name"
                        >
                    </div>

                    <div class="settings-card__password-inputs">
                        <div class="settings-card__input-block">
                            <label for="new-password">New password</label>
                            <input type="password" id="new-password"
                                   :disabled="disabled"
                                   v-model="editedFields.newPassword"
                            >
                        </div>

                        <div class="settings-card__input-block">
                            <label for="new-password-confirmation">New password confirmation</label>
                            <input type="password" id="new-password-confirmation"
                                   :disabled="disabled"
                                   v-model="editedFields.newPasswordConfirmation"
                            >
                        </div>
                    </div>

                    <div class="settings-card__input-block">
                        <label for="current-password">Current password</label>
                        <input type="password" id="current-password"
                               :disabled="disabled"
                               v-model="currentPassword"
                        >
                    </div>

                </form>

            </div>

            <!-- Button menu -->
            <div class="settings-card__btn-menu">
                <div class="settings-card__editing-btn" v-if="editing">

                    <button class="settings-card__cancel-btn" @click="cancelHandler">cancel</button>

                    <button class="settings-card__save-btn" @click="saveHandler">save</button>

                </div>

                <button class="settings-card__edit-btn" @click="editHandler" v-else>edit</button>
            </div>

        </div>
    </div>
</template>

<script>
import { ref, reactive } from 'vue';
import { useStore } from 'vuex';
import settingsRequest from '~/api/settingsRequest';

export default {
    name: 'Settings',

    setup() {
        /* Common */

        const store = useStore();

        const disabled = ref(true);

        const clearPasswordInputs = () => {
            editedFields.newPassword = '';
            editedFields.newPasswordConfirmation = '';
            currentPassword.value = '';
        };

        const fieldsValid = () => {
            if (currentPassword.value.length < 8) {
                return false;
            }

            if (editedFields.name < 1) {
                return false;
            }

            if (editedFields.newPassword !== '' && (editedFields.newPassword < 8
                || editedFields.newPassword !== editedFields.newPasswordConfirmation)) {
                return false;
            }

            return true;
        };

        const prepareRequestData = () => {
            const data = {
                password: currentPassword.value,
            };

            if (editedFields.name !== beforeEdit.name) {
                data.name = editedFields.name;
            }

            if (editedFields.newPassword !== '') {
                data.newPassword = editedFields.newPassword;
                data.newPasswordConfirmation = editedFields.newPasswordConfirmation;
            }

            return data;
        };

        /* Editing */

        const editing = ref(false);
        const beforeEdit = reactive(store.state.user);

        const editedFields = reactive({
            name: store.state.user.name,
            email: store.state.user.email,
            newPassword: '',
            newPasswordConfirmation: '',
        });

        const currentPassword = ref('');

        /* Handler */

        const editHandler = () => {
            editing.value = true;
            disabled.value = false;
        };

        const cancelHandler = () => {
            editedFields.name = beforeEdit.name;
            clearPasswordInputs();
            editing.value = false;
            disabled.value = true;
        };

        const saveHandler = async () => {
            disabled.value = true;

            if (!fieldsValid()) {
                disabled.value = false;
                return;
            }

            const fields = prepareRequestData();

            if (Object.keys(fields).length === 1) {
                disabled.value = false;
                return;
            }

            const res = await settingsRequest(fields);

            if (!res.status) {
                // TODO: add error output
                disabled.value = false;
                return;
            }

            store.commit('UPDATE_USER', fields);

            disabled.value = false;
        };

        return {
            disabled,
            editing,
            editedFields,
            currentPassword,
            editHandler,
            cancelHandler,
            saveHandler,
        };
    },
};
</script>

<style lang="scss">
.settings {
    @apply flex justify-center items-center h-full bg-gray-50;
}

.settings-card {
    width: 40rem;

    @apply flex flex-col justify-between space-y-8 py-7 px-8;
    @apply rounded shadow-lg bg-white;

    &__header {
        @apply text-3xl font-bold;
    }

    &__form-block {
        @apply flex-grow space-y-5;

        .settings-card__user-email {
            @apply text-lg;
        }
    }

    &__input-block {
        @apply mt-5;

        input {
            @apply w-full mt-1;
            @apply rounded-md shadow-sm border-gray-300;
            @apply focus:ring-1 focus:ring-yellow-600 focus:border-yellow-600 focus:ring-opacity-75;
        }

        label {
            @apply block font-medium text-sm text-gray-700;
        }
    }

    &__btn-menu {
        @apply flex justify-end;

        button {
            @apply py-3 px-6;
            @apply shadow-md rounded-full focus:outline-none;
        }

        .settings-card__save-btn, .settings-card__edit-btn {
            @apply text-white bg-yellow-500;
        }
    }

    &__editing-btn {
        @apply space-x-3;
    }
}
</style>
