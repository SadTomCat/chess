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

                <div class="settings-card__change-result-message">
                    <p class="settings-card__error-message">{{ errors.message }}</p>

                    <p class="settings-card__successful-message" v-if="isChangeSuccessful">
                        Changes have been successful.
                    </p>
                </div>

                <form>

                    <div class="settings-card__input-block">
                        <label for="name">Name</label>
                        <input id="name"
                               type="text"
                               :disabled="disabled"
                               v-model="editedFields.name"
                        >
                        <p class="settings-card__input-error">{{ errors.name }}</p>
                    </div>

                    <div class="settings-card__input-block">
                        <label for="new-password">New password</label>
                        <input id="new-password"
                               type="password"
                               :disabled="disabled"
                               v-model="editedFields.newPassword"
                        >
                        <p class="settings-card__input-error">{{ errors.newPassword }}</p>
                    </div>

                    <div class="settings-card__input-block">
                        <label for="new-password-confirmation">New password confirmation</label>
                        <input id="new-password-confirmation"
                               type="password"
                               :disabled="disabled"
                               v-model="editedFields.newPasswordConfirmation"
                        >
                    </div>

                    <div class="settings-card__input-block">
                        <label for="current-password">Current password</label>
                        <input id="current-password"
                               type="password"
                               :disabled="disabled"
                               v-model="currentPassword"
                        >
                        <p class="settings-card__input-error">{{ errors.password }}</p>
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
        const isChangeSuccessful = ref(false);

        /* Errors */
        const errors = reactive({
            message: '',
            name: '',
            newPassword: '',
            password: '',
        });

        const printErrors = (resErrors) => {
            if (resErrors instanceof String) {
                errors.message = resErrors;
                return;
            }

            Object.getOwnPropertyNames(resErrors).forEach((el) => {
                if (errors.hasOwnProperty(el) === true) {
                    errors[el] = resErrors[el];
                } else {
                    errors.message = resErrors[el];
                }
            });
        };

        const clearErrors = () => {
            Object.getOwnPropertyNames(errors).forEach((el) => {
                errors[el] = '';
            });
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

        const clearPasswordInputs = () => {
            editedFields.newPassword = '';
            editedFields.newPasswordConfirmation = '';
            currentPassword.value = '';
        };

        const fieldsValid = () => {
            if (currentPassword.value === '') {
                errors.password = 'Password must be required';
                return false;
            }

            if (currentPassword.value.length < 8) {
                errors.password = 'Password must be more 8 characters';
                return false;
            }

            if (editedFields.name < 1) {
                errors.name = 'Name must be required';
                return false;
            }

            if (editedFields.newPassword !== '' && editedFields.newPassword.length < 8) {
                errors.newPassword = 'Password must be more 8 characters';
                return false;
            }

            if (editedFields.newPassword !== editedFields.newPasswordConfirmation) {
                errors.newPassword = 'New password and password confirmation not same';
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

        /* Handler */

        const editHandler = () => {
            editing.value = true;
            disabled.value = false;
        };

        const cancelHandler = () => {
            editedFields.name = beforeEdit.name;
            clearPasswordInputs();
            clearErrors();
            editing.value = false;
            disabled.value = true;
            isChangeSuccessful.value = false;
        };

        const saveHandler = async () => {
            disabled.value = true;
            clearErrors();

            if (fieldsValid() === false) {
                disabled.value = false;
                return;
            }

            const fields = prepareRequestData();

            if (Object.keys(fields).length === 1) {
                disabled.value = false;
                isChangeSuccessful.value = false;
                errors.message = 'You have nothing edited';
                return;
            }

            const res = await settingsRequest(fields);

            clearPasswordInputs();
            if (res.status === false) {
                printErrors(res.errors);
                disabled.value = false;
                return;
            }

            store.commit('UPDATE_USER', fields);
            isChangeSuccessful.value = true;
            disabled.value = false;
        };

        return {
            disabled,
            isChangeSuccessful,
            errors,
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

    &__input-block:not(&__input-block:first-child) {
        @apply mt-9;
    }

    &__input-block {
        @apply relative;

        input {
            @apply w-full mt-1;
            @apply rounded-md shadow-sm border-gray-300;
            @apply focus:ring-1 focus:ring-yellow-600 focus:border-yellow-600 focus:ring-opacity-75;
        }

        label {
            @apply block font-medium text-sm text-gray-700;
        }
    }

    &__change-result-message {
        @apply relative h-8;

        .settings-card__error-message {
            @apply text-red-600 text-xl;
        }

        .settings-card__successful-message {
            @apply text-green-600 text-xl;
        }
    }

    &__input-error {
        @apply absolute text-sm text-red-600;
        bottom: -1.4rem;
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
