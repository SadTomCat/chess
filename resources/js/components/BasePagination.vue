<template>
    <div class="base-pagination">

        <div class="inline-block" v-if="pageForDisplay[0] !== 1">
            <span class="base-pagination__page" @click="$emit('newPageAction', 1)">{{ 1 }}</span>

            <span class="base-pagination__ellipsis">...</span>
        </div>

        <span class="base-pagination__page"
              @click="$emit('newPageAction', page)"
              v-for="page in pageForDisplay"
              :class="page === currentPage ? 'base-pagination__page_current': ''"
        >
            {{ page }}
        </span>

        <div class="inline-block" v-if="pageForDisplay[pageForDisplay.length - 1] !== totalPages">
            <span class="base-pagination__ellipsis">...</span>

            <span class="base-pagination__page" @click="$emit('newPageAction', totalPages)">{{ totalPages }}</span>
        </div>
    </div>
</template>

<script>
import { reactive, watchEffect } from 'vue';

export default {
    name: 'BasePagination',

    props: {
        currentPage: {
            type: Number,
            required: true,
        },
        totalPages: {
            type: Number,
            required: true,
        },
    },

    setup(props) {
        const countDisplayPages = 3;
        const middleElement = Math.round(countDisplayPages / 2);
        const pageForDisplay = reactive([]);

        watchEffect(() => {
            pageForDisplay.length = 0;
            let first = props.currentPage - middleElement <= 0
                ? 1
                : props.currentPage - middleElement + 1;

            const last = first + countDisplayPages - 1 < props.totalPages
                ? first + countDisplayPages - 1
                : props.totalPages;

            if ((last - first !== countDisplayPages - 1) && (first !== 1)) {
                while ((last - first !== countDisplayPages - 1) && (first !== 1)) {
                    first--;
                }
            }

            for (let i = first; i <= last; i++) {
                pageForDisplay.push(i);
            }
        });

        return {
            pageForDisplay,
        };
    },
};
</script>

<style lang="scss">
.base-pagination {
    height: fit-content;
    @apply flex justify-end;

    &__page {
        @apply inline-block py-2 px-4 mr-2 bg-yellow-500 rounded-full cursor-pointer;
    }

    &__page_current {
        @apply bg-yellow-700 text-white;
    }

    &__ellipsis {
        @apply mr-2 text-xl;
    }
}
</style>
