<template>
    <div class="chess-table relative">
        <div class="flex " v-for="i in 8" :key="i">
            <div class="h-20 w-20 cursor-pointer"
                 v-for="j in 8" :key="j"
                 :class="cellColor(j) ? 'bg-yellow-400' : 'bg-yellow-700'"
                 @click="moveHandler(i - 1, j - 1)"
            >
                <p class="chess-table__figure"
                   v-html="getFigure(i, j)"
                   :class="i - 1 === fromMove.x && j - 1 === fromMove.y ? 'chess-table__figure_selected' : ''"
                >
                </p>
            </div>
        </div>

        <chess-table-loader v-if="tableLoading"></chess-table-loader>
    </div>
</template>

<script>
import { onBeforeUnmount, reactive, watch } from 'vue';
import ChessTableLoader from './ChessTableLoader.vue';

export default {
    name: 'ChessTable',

    props: {
        color: {
            type: String,
        },
        moves: {
            type: Array,
        },
        canMove: {
            type: Boolean,
        },
        tableLoading: {
            type: Boolean,
        },
        opponentMove: {
            type: Object,
        },
    },

    setup(props, { emit }) {
        /* Table Settings */
        const playerColor = props.color;

        const figurinesCode = {
            p: '&#9817;',
            k: '&#9818;',
            q: '&#9819;',
            r: '&#9820;',
            b: '&#9821;',
            n: '&#9822;',
        };

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
            ['r', 'n', 'b', 'q', 'k', 'b', 'n', 'r'],
            ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'],
            ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R'],
        ]);

        const fromMove = reactive({
            x: -1,
            y: -1,
        });

        const getFigure = (i, j) => {
            const figure = figuresPosition[i - 1][j - 1];
            let color = 'text-white';

            if (figure === figure.toLowerCase()) {
                color = 'text-black';
            }

            const code = figurinesCode[figure.toLowerCase()] ?? '';

            return `<span class="${color}">${code}</span>`;
        };

        const selectedOwnFigure = (x, y) => {
            if (playerColor === 'white' && figuresPosition[x][y].toUpperCase() === figuresPosition[x][y]) {
                return true;
            }

            if (playerColor === 'black' && figuresPosition[x][y].toLowerCase() === figuresPosition[x][y]) {
                return true;
            }

            return false;
        };

        const move = (from, to) => {
            const tmp = figuresPosition[from.x][from.y];
            figuresPosition[from.x][from.y] = figuresPosition[to.x][to.y];
            figuresPosition[to.x][to.y] = tmp;

            fromMove.x = -1;
            fromMove.y = -1;
        };

        const moveHandler = (x, y) => {
            if (!props.canMove) {
                return;
            }

            if (fromMove.x > -1 && fromMove.y > -1) {
                emit('move', {
                    from: { x: fromMove.x, y: fromMove.y },
                    to: { x, y },
                });
            } else if (figuresPosition[x][y] !== '') {
                if (!selectedOwnFigure(x, y)) {
                    return;
                }

                fromMove.x = x;
                fromMove.y = y;
            }
        };

        watch(props.opponentMove, (opponentMove) => {
            move(opponentMove.from, opponentMove.to);
        });

        watch(props.moves, (moves) => {
            moves.forEach((el) => {
                move(el.from, el.to);
            });
        });

        /* Listener */
        function escHandler(e) {
            if (e.code !== 'Escape') {
                return;
            }

            if (fromMove.x !== -1) {
                fromMove.x = -1;
                fromMove.y = -1;
            }
        }

        window.addEventListener('keydown', escHandler);

        /* Hooks */
        onBeforeUnmount(() => {
            window.removeEventListener('keydown', escHandler);
        });

        return {
            cellColor,
            figuresPosition,
            fromMove,
            moveHandler,
            getFigure,
        };
    },

    components: { ChessTableLoader },
};
</script>

<style lang="scss">
.chess-table__figure {
    @apply block h-full flex justify-center items-center text-6xl select-none;

    &_selected {
        @apply border-4 border-green-400;
    }
}
</style>
