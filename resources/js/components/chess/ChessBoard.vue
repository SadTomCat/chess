<template>
    <div class="chess-board relative">
        <div class="flex " v-for="i in 8" :key="i">
            <div class="h-20 w-20 cursor-pointer"
                 v-for="j in 8" :key="j"
                 :class="cellColor(j) ? 'bg-yellow-400' : 'bg-yellow-700'"
                 @click="moveHandler(i - 1, j - 1)"
            >
                <p class="chess-board__chessman"
                   v-html="getChessman(i, j)"
                   :class="i - 1 === fromMove.x && j - 1 === fromMove.y ? 'chess-board__chessman_selected' : ''"
                >
                </p>
            </div>
        </div>

        <chess-board-loader v-if="boardLoading"></chess-board-loader>
    </div>
</template>

<script>
import { onBeforeUnmount, reactive, watch } from 'vue';
import ChessBoardLoader from './ChessBoardLoader.vue';

export default {
    name: 'ChessBoard',

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
        boardLoading: {
            type: Boolean,
        },
        opponentMove: {
            type: Object,
        },
    },

    setup(props, { emit }) {
        /* Board Settings */
        const playerColor = props.color;

        const chessmenCode = {
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

        /* Chessmen move */
        const board = reactive([
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

        const getChessman = (i, j) => {
            const chessman = board[i - 1][j - 1];
            let color = 'text-white';

            if (chessman === chessman.toLowerCase()) {
                color = 'text-black';
            }

            const code = chessmenCode[chessman.toLowerCase()] ?? '';

            return `<span class="${color}">${code}</span>`;
        };

        const selectedOwnChessman = (x, y) => {
            if (playerColor === 'white' && board[x][y].toUpperCase() === board[x][y]) {
                return true;
            }

            if (playerColor === 'black' && board[x][y].toLowerCase() === board[x][y]) {
                return true;
            }

            return false;
        };

        const move = (from, to, type = 'peace') => {
            if (from.x === to.x && from.y === to.y) {
                return;
            }

            board[to.x][to.y] = board[from.x][from.y];
            board[from.x][from.y] = '';

            fromMove.x = -1;
            fromMove.y = -1;

            if (type === 'aisle') {
                const dir = to.x > from.x ? 1 : -1;
                board[to.x - dir][to.y] = '';
            }
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

                fromMove.x = -1;
                fromMove.y = -1;
            } else if (board[x][y] !== '') {
                if (!selectedOwnChessman(x, y)) {
                    return;
                }

                fromMove.x = x;
                fromMove.y = y;
            }
        };

        watch(props.opponentMove, (opponentMove) => {
            move(opponentMove.from, opponentMove.to, opponentMove.type);
        });

        watch(props.moves, (moves) => {
            moves.forEach((el) => {
                move(el.from, el.to, el.type);
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
            board,
            fromMove,
            moveHandler,
            getChessman,
        };
    },

    components: { ChessBoardLoader },
};
</script>

<style lang="scss">
.chess-board__chessman {
    @apply block h-full flex justify-center items-center text-6xl select-none;

    &_selected {
        @apply border-4 border-green-400;
    }
}
</style>
