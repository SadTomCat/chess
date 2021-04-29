<template>
    <div class="forgot-password__wrapper">
        <div class="forgot-password__card">

            <!-- Header -->
            <div class="forgot-password__header">
                <span class="material-icons forgot-password-header__back" @click="backHandler">arrow_back_ios</span>
                <h1>Forgot password</h1>
            </div>

            <!-- Message about page -->
            <p class="forgot-password__text">Forgot your password? No problem. Just let us know your email address and
                we will email you a password
                reset link that will allow you to choose a new one.
            </p>

            <!-- Input -->
            <div class="relative forgot-password__input-block auth__input-wrapper">
                <label for="email">email</label>
                <input id="email" type="email" placeholder="email" v-model.trim="email" :disabled="disabled">
                <p class="forgot-password__error">{{ error }}</p>
                <p class="forgot-password__successful">{{ successful }}</p>
            </div>

            <!-- Buttons menu -->
            <div class="forgot-password__button-block">
                <button @click="sendResetLink" :disabled="disabled" v-if="successful.length === 0">
                    Send reset link
                </button>

                <button @click="router.replace('/')" v-else>go to main</button>
            </div>

        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import forgotPasswordRequest from '~/api/forgotPasswordRequest';

export default {
    name: 'ForgotPassword',

    setup() {
        const router = useRouter();

        const email = ref('');

        const disabled = ref(false);

        const error = ref('');
        const successful = ref('');

        const backHandler = () => {
            router.replace('/login');
        };

        const sendResetLink = async () => {
            error.value = '';
            successful.value = '';

            disabled.value = true;

            const res = await forgotPasswordRequest(email.value);

            if (res.status) {
                successful.value = res.message;
            } else {
                error.value = res.message;
                disabled.value = false;
            }
        };

        return {
            email,
            sendResetLink,
            disabled,
            backHandler,
            error,
            successful,
            router,
        };
    },
};
</script>

<style lang="scss">
.forgot-password__wrapper {
    @apply flex justify-center items-center h-screen w-screen bg-gray-100;
}

.forgot-password__card {
    @apply flex flex-col py-6 px-6 space-y-6;
    @apply bg-white shadow-lg rounded-md;
    width: 28rem;

    .forgot-password__text {
        @apply text-sm text-gray-700;
    }
}

.forgot-password__header {
    @apply flex justify-center mb-4 relative;

    .forgot-password-header__back {
        @apply flex items-center absolute inset-y-0 left-0 cursor-pointer;
    }

    h1 {
        @apply text-3xl text-center inline;
        @apply bg-gradient-to-r from-yellow-500 to-pink-500;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
}

.forgot-password__input-block {
    @apply flex flex-col space-y-1 text-sm pb-6;

    .forgot-password__successful, .forgot-password__error {
        @apply absolute bottom-0;
    }

    .forgot-password__error {
        @apply text-red-700;
    }

    .forgot-password__successful {
        @apply text-green-700;
    }
}

.forgot-password__button-block {
    @apply flex justify-end;

    button {
        @apply uppercase py-2 px-7 text-sm text-white rounded-full bg-gray-700;
    }
}
</style>
