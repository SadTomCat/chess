<template>
    <div class="chess-board-loader">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>
</template>

<script>
export default {
    name: 'ChessBoardLoader',
};
</script>

<style lang="scss">
.chess-board-loader {
    height: 40rem;
    width: 40rem;
    background: rgba(60, 66, 90, 0.4);
    @apply flex justify-center items-center absolute top-0;

    .lds-ring {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }
    .lds-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 64px;
        height: 64px;
        margin: 4px;
        border: 4px solid #fff;
        border-radius: 50%;
        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #fff transparent transparent transparent;
    }
    .lds-ring div:nth-child(1) {
        animation-delay: -0.45s;
    }
    .lds-ring div:nth-child(2) {
        animation-delay: -0.3s;
    }
    .lds-ring div:nth-child(3) {
        animation-delay: -0.15s;
    }
    @keyframes lds-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
}
</style>
