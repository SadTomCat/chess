<template>
    <auth-card :title="'Login'" :actionBtnName="'login'" @authAction="loginHandler">

        <!-- Email input  -->
        <div class="auth__input-wrapper login__email-block">
            <label for="email">email</label>
            <input id="email" type="email" placeholder="email">
        </div>

        <!-- Password input  -->
        <div class="auth__input-wrapper login__password-block">
            <label for="password">password</label>
            <input id="password" type="password" placeholder="password">
        </div>

        <!-- Remember me checkbox  -->
        <div class="login__remember-block">
            <input id="remember" type="checkbox">
            <label for="remember">remember me</label>
        </div>

    </auth-card>
</template>

<script>
import AuthCard from '~/components/AuthCard.vue';

export default {
    name: 'Login',

    components: { AuthCard },

    setup() {
        const loginHandler = () => {
            console.log('login');
        };

        return { loginHandler };
    },
};
</script>

<style lang="scss">
.login__email-block, .login__remember-block {
    @apply mb-8;
}

.login__password-block {
    @apply mb-4;
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
