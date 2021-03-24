<template>
    <div class="search-game-error">
        <div class="search-game-error__reasons" v-if="reasons.length > 0">
            <h1>You got problem. Some reasons</h1>

            <ul>
                <li v-for="reason in reasons" :key="reason">{{ reason }}</li>
            </ul>
        </div>

        <div class="search-game-error__solutions" v-if="solutions.length > 0">
            <h1>Try this</h1>

            <ul>
                <li v-for="solution in solutions" :key="solution">{{ solution }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SearchGameError',

    props: {
        reasons: Array,
        solutions: Array,
    },
};
</script>

<style lang="scss">
.search-game-error {
    @apply flex flex-col justify-between space-y-6;

    ul {
        @apply pl-9 list-disc;
    }

    h1 {
        @apply text-2xl;
    }
}
</style>
