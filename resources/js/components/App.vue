<template>
    <header-block>

    </header-block>

    <main>
        <router-view></router-view>
    </main>

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
