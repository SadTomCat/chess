<template>
    <auth-card :title="'Registration'" :actionBtnName="'register'" @authAction="registerHandler">

        <div class="registration__top">
            <div class="auth__input-wrapper">
                <label for="email">email</label>
                <input id="email"
                       type="email"
                       placeholder="email"
                       v-model="userInput.email"
                       @input="inputError.email = ''"
                >

                <p class="auth__error-message">{{inputError.email}}</p>
            </div>

            <div class="auth__input-wrapper">
                <label for="name">name</label>
                <input
                    id="name"
                    type="text"
                    placeholder="name"
                    v-model="userInput.name"
                    @input="inputError.name = ''"
                >
                <p class="auth__error-message">{{inputError.name}}</p>
            </div>
        </div>

        <div class="registration__bottom">
            <div class="auth__input-wrapper">
                <label for="password">password</label>
                <input id="password"
                       type="password"
                       placeholder="password"
                       v-model="userInput.password"
                       @input="inputError.password = ''"
                >
                <p class="auth__error-message">{{inputError.password}}</p>
            </div>

            <div class="auth__input-wrapper">
                <label for="confirm-password">confirm password</label>
                <input id="confirm-password"
                       type="password"
                       placeholder="confirm password"
                       v-model="userInput.password_confirmation"
                >
            </div>
        </div>

    </auth-card>
</template>

<script>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import AuthCard from '~/components/AuthCard.vue';
import registerRequest from '~/api/registerRequest';

export default {
    name: 'Registration',

    components: { AuthCard },

    setup() {
        const router = useRouter();

        const store = useStore();

        const userInput = reactive({
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        });

        const inputError = reactive({
            name: '',
            email: '',
            password: '',
        });

        const printError = (res) => {
            const errors = res.errors ?? {};

            for (const [key, value] of Object.entries(errors)) {
                inputError[key] = value[0];
            }
        };

        const registerHandler = async () => {
            const data = await registerRequest({
                name: userInput.name,
                email: userInput.email,
                password: userInput.password,
                password_confirmation: userInput.password_confirmation,
            });

            if (!data.status) {
                printError(data);
            }

            const { user } = data;
            user.logged = true;

            store.commit('SET_USER', user);
            await router.replace('/');
        };

        return { registerHandler, userInput, inputError };
    },
};
</script>

<style>
.registration__top {
    @apply flex space-x-7 mb-10;
}

.registration__bottom {
    @apply space-y-10 mb-10;
}
</style>
