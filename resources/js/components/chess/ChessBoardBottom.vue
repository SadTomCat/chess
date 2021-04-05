<template>
    <div class="chess-board-bottom py-5 text-xl">
        <p>Info: <span class="text-red-600">{{ message }}</span></p>
    </div>
</template>

<script>
export default {
    name: 'ChessBoardBottom',

    props: {
        message: String,
    },
};
</script>
