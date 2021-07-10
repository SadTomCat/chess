<template>
    <auth-card :title="'Login'" :action-btn-name="'login'" @authAction="loginHandler">

        <!-- Email input  -->
        <auth-input-wrapper class="login__email-block">
            <label for="email">email</label>
            <input id="email" type="email" placeholder="email" v-model="userInput.email">
            <p class="auth__error-message">{{ inputError.email }}</p>
        </auth-input-wrapper>

        <!-- Password input  -->
        <auth-input-wrapper class="login__email-block">
            <label for="password">password</label>
            <input id="password" type="password" placeholder="password" v-model="userInput.password">
            <p class="auth__error-message">{{ inputError.password }}</p>
        </auth-input-wrapper>

        <!--  Remember me and forgot password      -->
        <div class="login__remember-and-forgot">

            <!-- Remember me checkbox  -->
            <div class="login__remember-block">
                <input id="remember" type="checkbox" v-model="userInput.remember">
                <label for="remember">remember me</label>
            </div>

            <!-- Forgot password  -->
            <router-link to="/forgot-password" class="underline">forgot password</router-link>

        </div>

    </auth-card>
</template>

<script>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import AuthCard from '~/components/AuthCard.vue';
import loginRequest from '../api/auth/loginRequest';
import AuthInputWrapper from '~/components/inputs/AuthInputWrapper.vue';

export default {
    name: 'Login',

    setup() {
        const router = useRouter();

        const store = useStore();

        const userInput = reactive({
            email: '',
            password: '',
            remember: false,
        });

        const inputError = reactive({
            email: '',
            password: '',
        });

        const printError = (res) => {
            const errors = res.errors ?? {};

            for (const [key, value] of Object.entries(errors)) {
                inputError[key] = value[0];
            }
        };

        const loginHandler = async () => {
            const data = await loginRequest(userInput);

            if (!data.status) {
                printError(data);
                return;
            }

            const { user } = data;
            user.logged = true;

            store.commit('SET_USER', user);
            await router.replace('/');
        };

        return {
            loginHandler,
            userInput,
            inputError,
        };
    },

    components: { AuthCard, AuthInputWrapper },
};
</script>

<style lang="scss">
.login__email-block, .login__remember-block {
    @apply mb-12;
}

.login__password-block {
    @apply mb-12;
}

.login__remember-and-forgot {
    @apply flex justify-between;
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
