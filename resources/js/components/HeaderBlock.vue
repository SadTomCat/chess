<template>
    <div class="header-wrapper" v-if="headerActive">

        <header class="header">

            <!-- Left block -->
            <div class="header__left-block">

                <!-- Logo -->
                <div class="header__logo">
                    <h1>Chess</h1>
                </div>

                <!-- Pages navigation -->
                <nav>
                    <ul class="header__horizontal-list">
                        <li>
                            <router-link to="/">main</router-link>
                        </li>
                        <li>
                            <router-link to="/rating">rating</router-link>
                        </li>
                        <li>
                            <router-link to="/chess-rules">rules</router-link>
                        </li>
                        <li>
                            <router-link to="/support">support</router-link>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Right block -->
            <div class="header__right-block">

                <!-- Auth navigation -->
                <nav v-if="logged === false">
                    <ul class="header__horizontal-list">
                        <li>
                            <router-link to="/login">login</router-link>
                        </li>
                        <li>
                            <router-link to="/registration">registration</router-link>
                        </li>
                    </ul>
                </nav>

                <!-- View profile block-->
                <view-profile :user="user"
                              @logout="logout"
                              v-else
                ></view-profile>
            </div>
        </header>

    </div>
</template>

<script>
import { useStore } from 'vuex';
import { computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import logoutRequest from '../api/auth/logoutRequest';
import useEchoHelper from '~/helpers/useEchoHelper';
import ViewProfile from './ViewProfile.vue';

export default {
    name: 'HeaderBlock',

    setup() {
        const router = useRouter();
        const route = useRoute();

        // Store
        const store = useStore();
        const user = computed(() => store.state.user);
        const headerActive = computed(() => store.state.headerActive);
        const logged = computed(() => store.state.user.logged);

        const { closeAllEchoChannels } = useEchoHelper();

        // Logout
        const logout = async () => {
            closeAllEchoChannels();
            const status = await logoutRequest();

            if (status) {
                store.commit('UNSET_USER');

                if (route.meta.auth === undefined) {
                    return;
                }

                await router.replace('/');
            }
        };

        return {
            headerActive,
            user,
            logged,
            logout,
        };
    },

    components: { ViewProfile },
};
</script>

<style lang="scss">
.header-wrapper {
    height: var(--header-height);
}

.header {
    @apply bg-white flex justify-between shadow-md px-32 py-6 z-30 flex fixed w-full;
    top: 0;

    &__logo {
        h1 {
            @apply text-4xl;
            @apply bg-gradient-to-r from-yellow-500 to-pink-500;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }

    &__horizontal-list {
        @apply flex space-x-8;
    }

    &__right-block, &__left-block {
        @apply flex items-center;
    }

    &__left-block {
        @apply space-x-32;
    }
}
</style>
