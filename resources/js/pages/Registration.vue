<template>
    <auth-card :title="'Registration'" :actionBtnName="'register'" @authAction="registerHandler">

        <div class="registration__top">
            <div class="auth__input-wrapper">
                <label for="email">email</label>
                <input id="email" type="email" placeholder="email">
            </div>

            <div class="auth__input-wrapper">
                <label for="name">name</label>
                <input id="name" type="text" placeholder="name">
            </div>
        </div>

        <div class="registration__bottom">
            <div class="auth__input-wrapper">
                <label for="password">password</label>
                <input id="password" type="text" placeholder="password">
            </div>

            <div class="auth__input-wrapper">
                <label for="confirm-password">confirm password</label>
                <input id="confirm-password" type="text" placeholder="confirm password">
            </div>
        </div>

    </auth-card>
</template>

<script>
import AuthCard from '~/components/AuthCard.vue';

export default {
    name: 'Registration',

    components: { AuthCard },

    setup() {
        const registerHandler = () => {
            console.log('register');
        };

        return { registerHandler };
    },
};
</script>

<style>
.registration__top {
    @apply flex space-x-7 mb-6;
}

.registration__bottom {
    @apply space-y-6 mb-6
}
</style>
