<template>
    <div class="admin-accounts-management">
        <div class="admin-accounts-management__tabs">
            <button class="admin-accounts-management__tab"
                    v-for="(tab, index) in tabs"
                    :key="tab.tabName"
                    :class="{'admin-accounts-management__tab_active': currentTabComponent === tab.componentName}"
                    @click="setCurrentTab(index)"
            >{{ tab.tabName }}
            </button>
        </div>

        <div class="admin-accounts-management__content-wrapper">
            <base-informer-block :error="information.error" :successful="information.successful">
            </base-informer-block>

            <keep-alive>
                <component :is="currentTabComponent"
                           :accessRoles="accessRoles"
                >
                    <template v-slot:admin-password>

                        <div class="admin-accounts-management">

                            <base-input-wrapper>
                                <label for="admin-password">You password</label>
                                <input type="password"
                                       id="admin-password"
                                       placeholder="You password"
                                       v-model="adminPassword"
                                >
                            </base-input-wrapper>

                        </div>

                    </template>
                </component>
            </keep-alive>
        </div>
    </div>
</template>

<script>
import {
    computed, onBeforeMount, provide, ref, readonly,
} from 'vue';
import AdminAccountsManagementCreateTab from './tabs/AdminAccountsManagementCreateTab.vue';
import AdminAccountsManagementEditRoleTab from './tabs/AdminAccountsManagementEditRoleTab.vue';
import baseInformerHelper from '../../../helpers/baseInformerHelper';
import BaseInformerBlock from '../../../components/informers/BaseInformerBlock.vue';
import adminAccessRolesRequest from '../../../api/admin/adminAccessRolesRequest';
import BaseInputWrapper from '../../../components/inputs/BaseInputWrapper.vue';
import UserValidation from '../../../validators/UserValidation';

export default {
    name: 'AdminAccountsManagement',

    setup() {
        /* ----------------- Errors ----------------- */
        const {
            information,
            setError,
            setSuccessful,
            setDefaultInformation,
        } = baseInformerHelper();

        provide('setError', setError);
        provide('setSuccessful', setSuccessful);
        provide('clearInformer', setDefaultInformation);

        /* ----------------- Roles and admin password ----------------- */
        const accessRoles = ref([]);
        const adminPassword = ref('');

        const updateAdminPassword = (value) => {
            adminPassword.value = value;
        };
        const isAdminPasswordValid = () => UserValidation.canUsePassword(adminPassword.value);

        provide('adminPassword', readonly(adminPassword));
        provide('updateAdminPassword', updateAdminPassword);
        provide('isAdminPasswordValid', isAdminPasswordValid);

        /* ----------------- Tabs ----------------- */
        const baseName = 'admin-accounts-management';

        const tabs = [
            {
                tabName: 'Create account',
                componentName: `${baseName}-create-tab`,
            },
            {
                tabName: 'Edit role',
                componentName: `${baseName}-edit-role-tab`,
            },
        ];

        const currentTabIndex = ref(0);

        const setCurrentTab = (index) => {
            if (index < 0 || index > tabs.length - 1) return;

            updateAdminPassword('');
            setDefaultInformation();
            currentTabIndex.value = index;
        };

        const currentTabComponent = computed(() => tabs[currentTabIndex.value].componentName);

        onBeforeMount(async () => {
            const res = await adminAccessRolesRequest();

            if (res.status === false) {
                return;
            }

            accessRoles.value = res.roles;
        });

        return {
            information,
            accessRoles,
            adminPassword,
            tabs,
            currentTabComponent,
            setCurrentTab,
        };
    },

    components: {
        BaseInputWrapper,
        BaseInformerBlock,
        AdminAccountsManagementCreateTab,
        AdminAccountsManagementEditRoleTab,
    },
};
</script>

<style lang="scss">
.admin-accounts-management {
    &__tabs {
        @apply flex  border-gray-300;
    }

    &__tab {
        @apply w-full bg-gray-300 py-3 text-xl focus:ring-0 focus:outline-none;

        &_active {
            @apply bg-opacity-0;
        }

        &:not(&:last-child) {
            @apply border-r border-gray-300;
        }
    }

    &__content-wrapper {
        @apply px-12 py-12;
    }
}
</style>
