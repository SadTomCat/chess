<template>
    <div class="admin-accounts-management-edit-role-tab">
        <base-search :placeholder="'enter email'"
                     @searchAction="searchAction"
                     @inputChange="setError('')"
        ></base-search>

        <div class="admin-accounts-management-edit-role-tab__user-info">

            <div class="admin-accounts-management-edit-role-tab__user-info_filled" v-if="isUserInfoFilled === true">
                <ul>
                    <li v-for="(propValue, propName) in userInfo" :key="propName">
                        {{ propName }}: {{ propValue }}
                    </li>
                </ul>

                <base-input-wrapper class="mb-8">
                    <label for="select-role">select role</label>

                    <select id="select-role" v-model="selectedRole">

                        <option v-for="accessRole in accessRoles" :value="accessRole" :key="accessRole">
                            {{ accessRole }}
                        </option>

                    </select>
                </base-input-wrapper>

                <slot name="admin-password"></slot>

                <button type="submit" @click="submit">update</button>
            </div>

            <h2 v-else>User is not selected</h2>

        </div>
    </div>
</template>

<script lang="ts">
import {
    computed, inject, reactive, ref,
} from 'vue';
import BaseSearch from '../../../../components/BaseSearch.vue';
import BaseInputWrapper from '../../../../components/inputs/BaseInputWrapper.vue';
import adminUserInfoRequest from '../../../../api/admin/adminUserInfoRequest';
import adminUpdateRoleRequest from '../../../../api/admin/adminUpdateRoleRequest';

export default {
    name: 'AdminAccountsManagementEditRoleTab',

    props: {
        accessRoles: Array,
    },

    setup() {
        const adminPassword = inject('adminPassword');
        const updateAdminPassword = inject('updateAdminPassword');
        const isAdminPasswordValid = inject('isAdminPasswordValid');

        const setError = inject('setError');
        const setSuccessful = inject('setSuccessful');

        /* ----------------- User and role ----------------- */
        const selectedRole = ref('');

        const userInfo = reactive({
            id: '',
            name: '',
            email: '',
            role: '',
            blocked: '',
        });

        const setUserInfo = (obj) => {
            Object.getOwnPropertyNames(userInfo).forEach((el) => {
                userInfo[el] = obj[el] ?? '';
            });
        };

        const userInfoProp = () => Object.getOwnPropertyNames(userInfo);

        const countUserInfoProp = () => userInfoProp().length;

        const countFilledUserInfoProp = () => userInfoProp().filter((p) => userInfo[p] !== '').length;

        const isUserInfoFilled = computed(() => countFilledUserInfoProp() === countUserInfoProp());

        /* ----------------- Search ----------------- */

        const searchAction = async (searched) => {
            updateAdminPassword('');
            setError('');

            if (searched.length === 0) return;

            if (searched.length < 3) {
                setError('Too short email');
                return;
            }

            const resUserInfo = await adminUserInfoRequest(searched, false);

            if (resUserInfo.status === false) {
                setError(resUserInfo.message);
                return;
            }

            setUserInfo(resUserInfo);
        };

        const submit = async () => {
            if (selectedRole.value === '') return;

            if (isAdminPasswordValid() === false) {
                setError('Incorrect you password');
                return;
            }

            setError('');

            const role = selectedRole.value;
            const res = await adminUpdateRoleRequest(userInfo.id, role, adminPassword.value);
            updateAdminPassword('');

            if (res.status === true) {
                userInfo.role = role;
                setSuccessful('Role has been updated');
                return;
            }

            setError(res.message ?? 'Something went wrong');
        };

        return {
            setError,
            selectedRole,
            userInfo,
            isUserInfoFilled,
            searchAction,
            submit,
        };
    },

    components: {
        BaseInputWrapper,
        BaseSearch,
    },
};
</script>

<style lang="scss">
.admin-accounts-management-edit-role-tab__user-info {
    @apply w-full px-8 py-10 mt-8;
    @apply rounded-xl bg-white shadow;

    &_filled {
        @apply flex flex-col;

        ul {
            @apply space-y-3;
        }
    }

    ul {
        @apply text-xl mb-8;
    }

    h2 {
        @apply text-gray-700 text-2xl;
    }

    button {
        @apply px-5 py-3 mt-10;
        @apply bg-gradient-to-br from-pink-500 to-yellow-500;
        @apply text-lg text-white self-end outline-none focus:ring-0 rounded-full;

        &:hover {
            @apply bg-gradient-to-l from-yellow-500 to-pink-500;
        }
    }
}
</style>
