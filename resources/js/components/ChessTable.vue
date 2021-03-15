<template>
    <div class="table relative">
        <div class="flex " v-for="i in 8" :key="i">
            <div class="h-20 w-20 cursor-pointer"
                 v-for="j in 8" :key="j"
                 :class="cellColor(j) ? 'bg-yellow-400' : 'bg-yellow-700'"
                 @click="move(i - 1, j - 1)"
            >
                <span class="bishop">{{ figuresPosition[i - 1][j - 1] }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive } from 'vue';

export default {
    name: 'ChessTable',

    setup() {
        /* Table Settings */
        const playerColor = 'white';

        /* Cell Color */
        let numRow = 0;

        const cellColor = (index) => {
            if (index === 1) {
                numRow++;
            }

            return (index + numRow) % 2 === 0;
        };

        /* Figures move */
        const figuresPosition = reactive([
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ]);

        let fromMove = {
            x: -1,
            y: -1,
        };

        /*
        * 1. Select - set from
        * 2. Make request?
        * 3. Move or not
        * */
        const move = (x, y) => {
            if (fromMove.x > -1 && fromMove.y > -1) {
                const tmp = figuresPosition[fromMove.x][fromMove.y];
                figuresPosition[fromMove.x][fromMove.y] = figuresPosition[x][y];
                figuresPosition[x][y] = tmp;

                fromMove = {
                    x: -1,
                    y: -1,
                };
            } else {
                fromMove = {
                    x,
                    y,
                };
            }
        };

        /* Listeners */
        const clickOnBlock = (e, i, j) => {
            console.log(e, i, j);
        };

        return {
            cellColor,
            clickOnBlock,
            figuresPosition,
            move,
        };
    },
};
</script>
