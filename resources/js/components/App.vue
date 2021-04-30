<template>
    <div class="flex flex-col h-screen">
        <header-block>

        </header-block>

        <main class="h-full">
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

</style>
