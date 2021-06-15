<template>
    <div class="flex flex-col min-h-screen relative">
        <header-block></header-block>

        <main class="min-h-full flex-grow">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
import HeaderBlock from './HeaderBlock.vue';

export default {
    name: 'App',

    components: { HeaderBlock },
};
</script>

<style lang="scss">
main {
    height: 1px;
    max-height: fit-content;
}
</style>
