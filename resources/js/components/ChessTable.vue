<template>
    <div class="table relative">

        <div class="flex " v-for="i in 8" :key="i">
            <div class="h-20 w-20 cursor-pointer"
                 v-for="j in 8" :key="j"
                 :class="color(j) ? 'bg-yellow-200' : 'bg-yellow-600'"
                 @click="clickOnBlock($event, i, j)"
            >
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ChessTable',

    setup() {
        let numRow = 0;

        const color = (index) => {
            if (index === 1) {
                numRow++;
            }

            return (index + numRow) % 2 === 0;
        };

        const clickOnBlock = (e, i, j) => {
            console.log(e, i, j);
        };

        return { color, clickOnBlock };
    },
};
</script>
