<template>
    <div class="chess-board">
        <div class="chess-board__row" v-for="i in 8" :key="i">
            <div class="chess-board__cell"
                 v-for="j in 8" :key="j"
                 :class="cellStyles(j)"
                 @click="moveHandler(i - 1, j - 1)"
            >
                <p class="chess-board__chessman"
                   :class="selectedStyle(i, j)"
                   v-html="getChessman(i, j)"
                ></p>
            </div>
        </div>

        <chess-board-loader v-if="store.getters.BOARD_LOADER_SHOWN"></chess-board-loader>
    </div>
</template>

<script>
import { useStore } from 'vuex';
import { onBeforeUnmount, reactive, watch } from 'vue';
import ChessBoardLoader from './ChessBoardLoader.vue';

export default {
    name: 'ChessBoard',

    setup(props, { emit }) {
        const store = useStore();

        let printFrom = 0;

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

        const cellStyles = (j) => ([
            cellColor(j) ? 'bg-yellow-400' : 'bg-yellow-700',
            store.getters.CAN_MOVE ? 'cursor-pointer' : '',
        ]);

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

        const resetBoard = () => {
            let i = 0;
            let j = 0;

            [
                ['r', 'n', 'b', 'q', 'k', 'b', 'n', 'r'],
                ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'],
                ['', '', '', '', '', '', '', ''],
                ['', '', '', '', '', '', '', ''],
                ['', '', '', '', '', '', '', ''],
                ['', '', '', '', '', '', '', ''],
                ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'],
                ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R'],
            ].forEach((raw) => {
                j = 0;
                raw.forEach((chessman) => {
                    board[i][j] = chessman;
                    j++;
                });
                i++;
            });
        };

        const fromMove = reactive({
            x: -1,
            y: -1,
        });

        const selectedStyle = (i, j) => (i - 1 === fromMove.x && j - 1 === fromMove.y
            ? 'chess-board__chessman_selected'
            : ''
        );

        const getChessman = (i, j) => {
            const chessman = board[i - 1][j - 1];
            let colorClass = 'text-white';

            if (chessman === chessman.toLowerCase()) {
                colorClass = 'text-black';
            }

            const chessmanCode = chessmenCode[chessman.toLowerCase()] ?? '';

            return `<span class="${colorClass}">${chessmanCode}</span>`;
        };

        const selectedOwnChessman = (x, y) => (
            (store.state.game.color === 'white' && board[x][y].toUpperCase() === board[x][y])
            || (store.state.game.color === 'black' && board[x][y].toLowerCase() === board[x][y])
        );

        const move = (from, to, type = 'peace') => {
            if ((from.x === to.x && from.y === to.y) || board[from.x][from.y] === '') {
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

            // At this moment the king is in a needle cell.
            if (type === 'castling') {
                let rook;
                let dir;

                if (to.y === 6) /* short */ {
                    rook = board[from.x][7];
                    board[from.x][7] = '';
                    dir = 1;
                } else /* long */ {
                    rook = board[from.x][0];
                    board[from.x][0] = '';
                    dir = -1;
                }

                board[from.x][from.y + dir] = rook;
            }

            if (type === 'promotion') {
                board[to.x][to.y] = to.x === 0 ? 'Q' : 'q';
            }
        };

        const moveHandler = (x, y) => {
            if (store.getters.CAN_MOVE === false) {
                return;
            }

            if (fromMove.x > -1 && fromMove.y > -1) {
                emit('move', {
                    from: {
                        x: fromMove.x,
                        y: fromMove.y,
                    },
                    to: {
                        x,
                        y,
                    },
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

        watch(store.state.game.moves, () => {
            const { moves } = store.state.game;

            for (printFrom; printFrom < moves.length; printFrom++) {
                move(moves[printFrom].from, moves[printFrom].to, moves[printFrom].type);
            }

            // if need back move
            if (moves.length < printFrom) {
                resetBoard();

                moves.forEach((el) => {
                    move(el.from, el.to, el.type);
                });

                printFrom--;
            }
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
            store,
            cellColor,
            cellStyles,
            board,
            fromMove,
            moveHandler,
            getChessman,
            selectedStyle,
        };
    },

    components: { ChessBoardLoader },
};
</script>

<style lang="scss">
.chess-board {
    @apply relative;

    &__row {
        @apply flex;
    }

    &__cell {
        @apply h-20 w-20;
    }

    &__chessman {
        @apply block h-full flex justify-center items-center text-6xl select-none;

        &_selected {
            @apply border-4 border-green-400;
        }
    }
}

</style>
