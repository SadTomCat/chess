<template>
    <div class="colored-side-panel">
        <ul class="colored-side-panel__list">
            <li class="colored-side-panel__list-item" v-for="link in links" :key="link.name">
                <span class="material-icons">{{ link.icon ?? '' }}</span>
                <router-link :to="link.path">{{ link.name }}</router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'ColoredSidePanel',

    props: {
        /** @property {UrlLink[]} props.links */
        links: {
            type: Array,
            validator: (value) => {
                let status = false;

                try {
                    status = window.isArrayOf(window.isUrlLink, value);
                } catch (e) {
                    if (process.env.MIX_DEBUG === true) {
                        console.log('ColoredSidePanel failed in links validation');
                    }
                }

                return status;
            },
        },
    },
};
</script>

<style lang="scss">
.colored-side-panel {
    @apply flex h-full max-h-screen w-72 fixed;
    @apply py-12 bg-gradient-to-t from-yellow-600 to-pink-600;
}

.colored-side-panel__list {
    @apply w-full text-white text-lg pl-4;

    &-item {
        @apply flex  py-3;

        a {
            @apply pl-2;
        }
    }
}
</style>
