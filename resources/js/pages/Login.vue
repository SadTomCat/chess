<template>
    <auth-card :title="'Login'" :actionBtnName="'login'" @authAction="loginHandler">

        <!-- Email input  -->
        <div class="auth__input-wrapper login__email-block">
            <label for="email">email</label>
            <input id="email" type="email" placeholder="email" v-model="userInput.email">
            <p class="auth__error-message">{{inputError.email}}</p>
        </div>

        <!-- Password input  -->
        <div class="auth__input-wrapper login__password-block">
            <label for="password">password</label>
            <input id="password" type="password" placeholder="password" v-model="userInput.password">
            <p class="auth__error-message">{{inputError.password}}</p>
        </div>

        <!-- Remember me checkbox  -->
        <div class="login__remember-block">
            <input id="remember" type="checkbox" v-model="userInput.remember">
            <label for="remember">remember me</label>
        </div>

    </auth-card>
</template>

<script>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import AuthCard from '~/components/AuthCard.vue';
import loginRequest from '~/api/loginRequest';

export default {
    name: 'Login',

    components: { AuthCard },

    setup() {
        const router = useRouter();

        const userInput = reactive({ email: '', password: '', remember: false });

        const inputError = reactive({ email: '', password: '' });

        const printError = (res) => {
            const errors = res.errors ?? {};

            for (const [key, value] of Object.entries(errors)) {
                inputError[key] = value[0];
            }
        };

        const loginHandler = async () => {
            const data = await loginRequest(userInput);

            if (data.status) {
                await router.replace('/');
                return;
            }

            printError(data);
        };

        return { loginHandler, userInput, inputError };
    },
};
</script>

<style lang="scss">
.login__email-block, .login__remember-block {
    @apply mb-10;
}

.login__password-block {
    @apply mb-10;
}

.login__remember-block {
    input[type=checkbox]:checked {
        @apply bg-yellow-600 hover:bg-yellow-600 focus:bg-yellow-600;
    }

    input {
        @apply mr-3 rounded focus:ring-0;
    }
}
</style>
