<template>
    <div class="view-profile" @click.stop>
        <img src="" class="view-profile__img" @click="switchViewProfileStatus">

        <!-- Menu -->
        <div class="view-profile__menu" v-if="viewProfileStatus">

            <div class="view-profile-menu__top">

                <!-- Welcome user-->
                <div class="view-profile-menu__welcome">
                    <h1>Hi, {{ user.name }}</h1>
                </div>

                <!-- List of links -->
                <ul>
                    <li>
                        <router-link to="/admin" v-if="showAdminPanelLink === true">admin panel</router-link>
                    </li>
                    <li>
                        <router-link to="/settings">settings</router-link>
                    </li>
                    <li>
                        <router-link to="/statistics">statistics</router-link>
                    </li>
                </ul>

            </div>

            <!-- Logout -->
            <a href="/logout" @click.prevent="$emit('logout')">logout</a>
        </div>
    </div>
</template>

<script>
import {
    computed, onBeforeUnmount, onMounted, ref,
} from 'vue';
import { useStore } from 'vuex';

export default {
    name: 'ViewProfile',

    props: {
        user: {
            type: Object,
        },
    },

    setup() {
        const store = useStore();

        const showAdminPanelLink = computed(() => store.state.user.role !== 'user');

        const viewProfileStatus = ref(false);

        const switchViewProfileStatus = () => {
            viewProfileStatus.value = !viewProfileStatus.value;
        };

        const closeViewProfile = () => {
            viewProfileStatus.value = false;
        };

        onMounted(() => {
            window.addEventListener('click', closeViewProfile);
            window.addEventListener('keydown', closeViewProfile);
        });

        onBeforeUnmount(() => {
            window.removeEventListener('click', closeViewProfile);
            window.addEventListener('keydown', closeViewProfile);
        });

        return {
            showAdminPanelLink,
            viewProfileStatus,
            switchViewProfileStatus,
            closeViewProfile,
        };
    },
};
</script>

<style lang="scss">
.view-profile {
    @apply relative;

    &__img {
        @apply w-10 h-10 rounded-full cursor-pointer overflow-hidden;
        @apply border border-gray-300;
    }

    &__menu {
        height: var(--view-profile-menu-height);

        transform-origin: top center;
        animation: growDown .1s linear;

        -webkit-box-shadow: 0px 4px 15px -3px rgba(31, 37, 41, 0.32);
        -moz-box-shadow: 0px 4px 15px -3px rgba(31, 37, 41, 0.32);
        box-shadow: 0px 4px 15px -3px rgba(31, 37, 41, 0.32);

        @apply flex flex-col justify-between overflow-y-hidden;
        @apply absolute right-0 w-64 px-3 py-4 mt-1 rounded-lg;
        @apply text-lg bg-white z-30;

        .view-profile-menu__top {
            @apply space-y-6 mb-5;
        }

        .view-profile-menu__welcome {
            @apply whitespace-nowrap overflow-x-hidden font-bold;
        }
    }

    ul {
        @apply space-y-5;
    }

    @keyframes growDown {
        0% {
            height: 0;
        }
        100% {
            height: var(--view-profile-menu-height);
        }
    }
}
</style>
