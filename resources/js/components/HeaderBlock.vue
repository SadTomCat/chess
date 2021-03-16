<template>
    <header class="header" v-if="headerActive">

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
                        <router-link to="/rules">rules</router-link>
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
            <nav v-if="!logged">
                <ul class="header__horizontal-list">
                    <li>
                        <router-link to="/login">login</router-link>
                    </li>
                    <li>
                        <router-link to="/registration">registration</router-link>
                    </li>
                </ul>
            </nav>

            <!-- User block-->
            <div class="auth-user" v-else>
                <img src="" class="auth-user__img" @click="switchAuthUserMenuStatus">

                <!-- Menu -->
                <div class="auth-user__menu" v-if="getAuthUserMenuStatus">

                    <ul>
                        <li>
                            <router-link to="/settings">settings</router-link>
                        </li>
                        <li>
                            <router-link to="/statistic">statistic</router-link>
                        </li>
                    </ul>

                    <a href="/logout" @click.prevent="logout">logout</a>
                </div>
            </div>

        </div>
    </header>
</template>

<script>
import { useStore } from 'vuex';
import { computed, ref } from 'vue';
import logoutRequest from '~/api/logoutRequest';

export default {
    name: 'HeaderBlock',

    setup() {
        // Store
        const store = useStore();
        const headerActive = computed(() => store.state.headerActive);
        const logged = computed(() => store.state.user.logged);

        // Auth user menu
        const authUserMenuStatus = ref(false);

        const getAuthUserMenuStatus = computed(() => authUserMenuStatus.value);

        const switchAuthUserMenuStatus = () => {
            authUserMenuStatus.value = !authUserMenuStatus.value;
        };

        // Logout
        const logout = async () => {
            const status = await logoutRequest();

            if (status) {
                store.commit('UNSET_USER');
            }
        };

        return {
            headerActive,
            getAuthUserMenuStatus,
            switchAuthUserMenuStatus,
            logged,
            logout,
        };
    },
};
</script>

<style lang="scss">
.header {
    @apply bg-white flex justify-between shadow-md px-32 py-6 z-30;

    h1 {
        @apply text-4xl;
        @apply bg-gradient-to-r from-yellow-500 to-pink-500;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
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

    .auth-user {
        @apply relative;

        &__img {
            @apply w-10 h-10 rounded-full cursor-pointer overflow-hidden;
            @apply border border-gray-300;
        }

        &__menu {
            @apply flex flex-col justify-between;
            @apply absolute right-0 w-64 h-64 px-3 py-4 mt-1 shadow-md;
            @apply text-lg bg-white shadow-md z-30;
        }

        ul {
            @apply space-y-5;

            li {
                @apply border-b border-gray-400;
            }
        }
    }
}
</style>
