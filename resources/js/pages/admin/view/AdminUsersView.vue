<template>
    <div class="admin-user-view">

        <div class="admin-user-view__top">
            <div class="admin-user-view-top__right">
                <img src="#" class="admin-user-view__img" alt="user image">

                <button class="admin-user-view__block-btn" @click="blockHandler" v-if="info['Blocked'] === false">
                    block
                </button>

                <button class="admin-user-view__unblock-btn" @click="unblockHandler" v-else>
                    unblock
                </button>
            </div>

            <ul class="space-y-5">
                <li v-for="(value, key) in info" :key="value">{{ key }}: {{ value }}</li>
            </ul>
        </div>

        <div class="admin-user-view__bottom">
            <user-games-list :user-id="Number($route.params.id)"
                             :more-info-route="`/admin/view/games`"
            ></user-games-list>
        </div>

    </div>
</template>

<script>
import { onBeforeMount, reactive } from 'vue';
import { useRoute } from 'vue-router';
import UserGamesList from '~/components/lists/UserGamesList.vue';
import stringHelper from '~/helpers/stringHelper';
import adminUserInfoRequest from '../../../api/admin/adminUserInfoRequest';
import adminUnblockUserRequest from '../../../api/admin/adminUnblockUserRequest';
import adminBlockUserRequest from '../../../api/admin/adminBlockUserRequest';

export default {
    name: 'AdminUsersView',

    setup() {
        const route = useRoute();
        const { upperFirstLetter } = stringHelper();

        const info = reactive({});

        const blockHandler = async () => {
            const data = await adminBlockUserRequest(route.params.id);

            if (data.status === false) {
                return;
            }

            info['Blocked at'] = data.blocked_at;
            info.Blocked = true;
        };

        const unblockHandler = async () => {
            const data = await adminUnblockUserRequest(route.params.id);

            if (data.status === false) {
                return;
            }

            info.Blocked = false;
            delete info['Blocked at'];
        };

        onBeforeMount(async () => {
            const res = await adminUserInfoRequest(route.params.id);

            if (res === false) {
                return;
            }

            Object.getOwnPropertyNames(res).forEach((el) => {
                if (el === 'created_at' || el === 'updated_at') {
                    info[upperFirstLetter(el.replace(/_/g, ' '))] = (new Date(res[el])).toUTCString();
                    return;
                }

                info[upperFirstLetter(el.replace(/_/g, ' '))] = res[el];

                if (el === 'not_count_games') {
                    info['Lose games'] = res.count_games - res.count_won - res.not_count_games;
                    info['Win rate'] = (res.count_won / info['Lose games']).toFixed(2);
                }
            });
        });

        return {
            info,
            blockHandler,
            unblockHandler,
        };
    },

    components: { UserGamesList },
};
</script>

<style lang="scss">
.admin-user-view {
    @apply flex flex-col py-8 px-10 w-full text-xl space-y-16;

    &__top {
        @apply flex space-x-12;
    }

    .admin-user-view-top__right {
        @apply flex flex-col justify-between;
    }

    &__img {
        @apply w-32 h-32 rounded-full overflow-hidden bg-black;
        @apply border border-gray-300;
    }

    &__block-btn {
        @apply focus:outline-none px-4 py-2 bg-red-600 text-white rounded-lg;
    }

    &__unblock-btn {
        @apply focus:outline-none px-4 py-2 bg-green-600 text-white rounded-lg;
    }
}
</style>
