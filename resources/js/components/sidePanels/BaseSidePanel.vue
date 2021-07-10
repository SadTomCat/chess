<!-- Sublinks was no test -->
<template>
    <div class="base-side-panel-wrapper">

        <div class="base-side-panel">

            <ul>
                <li class="base-side-panel__main-link"
                    :class="{'bg-gray-200': activeSubLinks === index}"
                    v-for="(link, index) in links"
                    :key="link"
                >

                    <span class="base-side-panel__show-sub-links material-icons"
                          @click="switchSubLinks(index)"
                          v-if="link.subLinks !== undefined"
                    >expand_more </span>

                    <router-link :to="link.link" @mouseup="locationWasChanged(index)">
                        {{ link.name }}
                    </router-link>

                    <!-- ---------- Sub links ---------- -->
                    <ul class="base-side-panel__sub-links"
                        v-if="link.subLinks !== undefined && activeSubLinks === index"
                    >

                        <li class="base-side-panel__sub-link"
                            v-for="(subLink, indexInSublinks) in link.subLinks"
                            :key="subLink"
                        >

                            <router-link :to="subLink.link"
                                         @mouseup="locationWasChangedInSubLinks(index, indexInSublinks)"
                            >{{ subLink.name }}</router-link>

                        </li>

                    </ul>

                </li>
            </ul>

        </div>

    </div>

</template>

<script>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

export default {
    name: 'BaseSidePanel',

    props: {
        links: {
            /**
             * Element {
             *     name: String,
             *     link: String,
             *     ?subLinks: Array [
             *          {
             *              name: String,
             *              link: String,
             *          }
             *     ]
             * }
             * */
            type: Array,
            required: true,
        },
    },

    emits: ['locationWasChanged', 'locationWasChangedInSubLinks'],

    setup(props, { emit }) {
        const activeSubLinks = ref(-1);

        const switchSubLinks = (index) => {
            if (index === activeSubLinks.value) {
                activeSubLinks.value = -1;
                return;
            }

            activeSubLinks.value = index;
        };

        const locationWasChanged = (index) => {
            emit('locationWasChanged', index);
        };

        const locationWasChangedInSubLinks = (index, indexInSubLinks) => {
            emit('locationWasChangedInSubLinks', index, indexInSubLinks);
        };

        return {
            activeSubLinks,
            switchSubLinks,
            locationWasChanged,
            locationWasChangedInSubLinks,
        };
    },
};
</script>

<style lang="scss">
.base-side-panel-wrapper {
    @apply flex w-96 h-full;
}

.base-side-panel {
    @apply w-96 py-12 h-full bg-white max-h-screen border-r-2 fixed z-10;

    ul {
        @apply space-y-3;
    }

    &__main-link {
        @apply flex flex-col w-full relative pl-8 text-lg;
    }

    &__show-sub-links {
        @apply absolute text-3xl cursor-pointer;
        left: 4px;
        top: -6px;
    }

    &__sub-links {
        @apply pl-8;
    }

    &__sub-link {
        @apply text-lg font-light;
    }
}
</style>
