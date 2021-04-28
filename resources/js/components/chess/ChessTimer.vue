<template>
    <div class="chess-timer flex justify-between bg-gray-300 px-4 h-8">
        <p>{{ time.minutes }}:{{ time.seconds }}</p>
    </div>
</template>

<script>
import { useStore } from 'vuex';
import {
    computed, onBeforeUnmount, reactive, watch,
} from 'vue';

export default {
    name: 'ChessTimer',

    setup() {
        let interval;
        const store = useStore();
        const moveEndAt = computed(() => store.state.game.moveEndAt);

        const time = reactive({
            seconds: 0,
            minutes: 0,
        });

        watch(moveEndAt, () => {
            const now = Math.floor(Date.now() / 1000);
            const endSeconds = store.state.game.moveEndAt - now - store.state.timeDifference;
            let endThrough = endSeconds <= 120 ? endSeconds : 120;
            const startThrough = endThrough - endSeconds;

            clearInterval(interval);

            setTimeout(() => {
                const timer = () => {
                    time.minutes = Math.floor((endThrough / 60) % 60);
                    time.seconds = endThrough % 60;

                    if (time.minutes === 0 && time.seconds === 0) {
                        store.commit('SET_TIME_ENDED', true);
                        clearInterval(interval);
                        return;
                    }

                    endThrough--;
                };

                if (endThrough > 0) {
                    store.commit('SET_TIMER_STARTED', true);
                    timer();
                    interval = setInterval(timer, 1000);
                }
            }, startThrough * 1000);
        });

        onBeforeUnmount(() => {
            clearInterval(interval);
        });

        return { time };
    },
};
</script>

<style>

</style>
