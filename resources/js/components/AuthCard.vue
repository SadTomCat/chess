<template>
    <div class="auth-card-wrap">
        <div class="auth-card">

            <!-- Title -->
            <h1 class="auth-card__title">
                <router-link to="/">{{ title }}</router-link>
            </h1>

            <form>

                <slot></slot>

                <!-- Button menu -->
                <div class="auth-card__btn-menu">
                    <button class="auth-card__back-btn" @click.prevent="$router.back()">back</button>

                    <button class="auth-card__action-btn" type="submit" @click.prevent="$emit('authAction')">
                        {{ actionBtnName }}
                    </button>
                </div>

            </form>

        </div>
    </div>
</template>

<script>
export default {
    name: 'AuthCard',

    props: {
        title: {
            type: String,
            required: true,
        },
        actionBtnName: {
            type: String,
            required: true,
        },
    },
};
</script>

<style lang="scss">
.auth-card-wrap {
    @apply w-screen h-screen flex flex-col justify-center items-center;
}

.auth-card {
    width: 33%;

    form {
        @apply px-6;
    }

    @apply pb-8 flex flex-col justify-between;
    @apply shadow-lg rounded-md;

    &__title {
        @apply w-full mb-8 py-4;
        @apply bg-gradient-to-r from-yellow-500 to-pink-500 rounded-t-md;
        @apply text-white text-4xl text-center;
    }

    &__btn-menu {
        @apply flex justify-end;

        button {
            @apply py-3 px-6;
            @apply shadow-md rounded-full focus:outline-none;
        }

        .auth-card__back-btn {
            @apply mr-4;
            @apply hover:bg-gray-200;
        }

        .auth-card__action-btn {
            @apply text-white;
            @apply bg-gradient-to-r from-yellow-500 to-pink-500;

            &:hover {
                @apply bg-gradient-to-br from-yellow-500 to-pink-500;
            }
        }
    }

    @media screen and (min-width: 1000px) and (max-width: 1500px) {
        width: 50%;
    }

    @media screen and (max-width: 1000px) {
        width: 60%;
    }
}

.auth__error-message {
    @apply absolute text-red-500;
}
</style>
