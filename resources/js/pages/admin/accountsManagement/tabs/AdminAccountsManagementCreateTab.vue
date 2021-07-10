<template>
    <div class="admin-accounts-management-create-tab">
        <span class="material-icons mb-6 text-4xl cursor-pointer" @click="clearInputs">autorenew</span>

        <form>
            <base-input-wrapper>
                <label for="user-name">name</label>
                <input id="user-name" type="text" placeholder="name" v-model.trim="newUser.name">
                <p class="admin-accounts-management-create-tab__form-error">{{ formErrors.name }}</p>
            </base-input-wrapper>

            <base-input-wrapper>
                <label for="user-email">email</label>
                <input id="user-email" type="email" placeholder="email" v-model.trim="newUser.email">
                <p class="admin-accounts-management-create-tab__form-error">{{ formErrors.email }}</p>
            </base-input-wrapper>

            <base-input-wrapper>
                <label for="user-role">role</label>
                <select id="user-role" v-model="newUser.role">
                    <option v-for="role in accessRoles" :key="role" :value="role">
                        {{ role }}
                    </option>
                </select>
                <p class="admin-accounts-management-create-tab__form-error">{{ formErrors.role }}</p>
            </base-input-wrapper>

            <base-input-wrapper>
                <label for="user-password">password</label>
                <input id="user-password" type="password" placeholder="password" v-model="newUser.password">
                <p class="admin-accounts-management-create-tab__form-error">{{ formErrors.password }}</p>
            </base-input-wrapper>

            <slot name="admin-password"></slot>
        </form>

        <button type="submit" @click="submit">submit</button>
    </div>
</template>

<script>
import { inject, reactive } from 'vue';
import BaseInputWrapper from '../../../../components/inputs/BaseInputWrapper.vue';
import UserValidation from '../../../../validators/UserValidation';
import adminCreateUserRequest from '../../../../api/admin/adminCreateUserRequest';

/**
 * Name is not required field
 * */
export default {
    name: 'AdminAccountsManagementCreateTab',

    props: {
        accessRoles: Array,
    },

    setup() {
        const adminPassword = inject('adminPassword');
        const updateAdminPassword = inject('updateAdminPassword');
        const isAdminPasswordValid = inject('isAdminPasswordValid');

        const setError = inject('setError');
        const setSuccessful = inject('setSuccessful');
        const clearInformer = inject('clearInformer');

        const userValidation = new UserValidation('', '', '');

        /* ------------ Form errors ------------ */
        const formErrors = reactive({
            name: '',
            email: '',
            password: '',
            role: '',
        });

        const clearFormErrors = () => {
            Object.getOwnPropertyNames(formErrors).forEach((p) => {
                formErrors[p] = '';
            });
        };

        const setFormErrors = (obj) => {
            if (window.isObject(obj) === false) {
                return;
            }

            clearFormErrors();
            Object.getOwnPropertyNames(formErrors).forEach((p) => {
                if (window.isString(obj[p]) && obj[p].length > 0) {
                    formErrors[p] = obj[p];
                }
            });
        };

        /* ------------ New user ------------ */

        const newUser = reactive({
            email: '',
            name: '',
            role: '',
            password: '',
        });

        const clearInputs = () => {
            Object.getOwnPropertyNames(newUser).forEach((prop) => {
                newUser[prop] = '';
            });

            clearFormErrors();
            updateAdminPassword('');
            clearInformer();
        };

        const validationBeforeSubmit = () => {
            const name = newUser.name === '' ? 'name not required' : newUser.name;
            userValidation.reset(name, newUser.email, newUser.password);
            const isRoleSelected = newUser.role.length > 0;

            if (userValidation.validate() === false || isRoleSelected === false) {
                setFormErrors({
                    ...(userValidation.getErrors()),
                    role: isRoleSelected === false ? 'Role is not selected' : '',
                });
                updateAdminPassword('');
                return false;
            }

            if (isAdminPasswordValid() === false) {
                setError('Invalid you password');
                updateAdminPassword('');
                return false;
            }

            return true;
        };

        const submit = async () => {
            clearFormErrors();

            if (validationBeforeSubmit() === false) return;

            clearInformer();

            const res = await adminCreateUserRequest(newUser, adminPassword.value);

            if (res.status === true) {
                setSuccessful('User has been created');
                return;
            }

            setError(res.message ?? 'Something went wrong');
        };

        return {
            formErrors,
            newUser,
            clearInputs,
            submit,
        };
    },

    components: { BaseInputWrapper },
};
</script>

<style lang="scss">
.admin-accounts-management-create-tab {
    @apply flex flex-col w-full px-8 py-6 rounded-xl bg-white shadow;

    form {
        @apply space-y-8 mb-10 flex flex-col;
    }

    button {
        @apply px-5 py-3;
        @apply bg-gradient-to-br from-pink-500 to-yellow-500;
        @apply text-lg text-white self-end outline-none focus:ring-0 rounded-full;

        &:hover {
            @apply bg-gradient-to-l from-yellow-500 to-pink-500;
        }
    }

    &__form-error {
        @apply mt-1 text-red-600;
    }
}
</style>
