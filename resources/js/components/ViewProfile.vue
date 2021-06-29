<template>
    <div class="view-profile">
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
import { ref } from 'vue';

export default {
    name: 'ViewProfile',

    props: {
        user: {
            type: Object,
        },
    },

    setup() {
        // View profile user menu
        const viewProfileStatus = ref(false);

        const switchViewProfileStatus = () => {
            viewProfileStatus.value = !viewProfileStatus.value;
        };

        return {
            viewProfileStatus,
            switchViewProfileStatus,
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
        @apply flex flex-col justify-between;
        @apply absolute right-0 w-64 h-64 px-3 py-4 mt-1 shadow-md;
        @apply text-lg bg-white shadow-md z-30;

        .view-profile-menu__top {
            @apply space-y-6;
        }

        .view-profile-menu__welcome {
            @apply whitespace-nowrap overflow-x-hidden font-bold;
        }
    }

    ul {
        @apply space-y-5;

        li {
            @apply border-b border-gray-400;
        }
    }
}
</style>
