<template>
    <div class="chess-timer flex justify-between bg-gray-300 px-4 h-8">
        <p>{{ time.minutes }}:{{ time.seconds }}</p>
    </div>
</template>

<script>
import { reactive, watch } from 'vue';
import useTimer from '~/helpers/useTimer';

export default {
    name: 'ChessTimer',

    props: {
        moveNum: {
            type: Number,
        },
    },

    setup(props, { emit }) {
        const { countdown } = useTimer();

        const time = reactive({
            seconds: 0,
            minutes: 0,
        });

        watch(props, () => {
            countdown(120, (timerTime) => {
                time.minutes = timerTime.minutes;
                time.seconds = timerTime.seconds;

                if (timerTime.minutes === 0 && timerTime.seconds === 0) {
                    emit('timeEnded');
                }
            });
        });

        return { time };
    },
};
</script>

<style>

</style>
