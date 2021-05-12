<template>
    <teleport to="body">
        <div class="base-pop-up__wrapper">
            <div class="base-pop-up" :style="{height, width}">
                <slot></slot>

                <button class="base-pop-up__close-btn" @click="$emit('closeAction')">
                    <span class="material-icons">close</span>
                </button>
            </div>
        </div>
    </teleport>
</template>

<script>
import { onBeforeUnmount, onMounted } from 'vue';

export default {
    name: 'BasePopUp',

    emits: ['closeAction'],

    props: {
        height: String,
        width: String,
    },

    setup(props, { emit }) {
        const escListener = (e) => {
            if (e.code !== 'Escape') {
                return;
            }

            emit('closeAction');
        };

        onMounted(() => {
            window.addEventListener('keydown', escListener);
        });

        onBeforeUnmount(() => {
            window.removeEventListener('keydown', escListener);
        });
    },
};
</script>

<style lang="scss">
.base-pop-up__wrapper {
    background-color: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(16px);
    @apply flex justify-center items-center absolute top-0 h-screen w-full z-30;
}

.base-pop-up {
    height: 30rem;
    width: 50rem;

    @apply relative bg-white rounded-2xl;

    &__close-btn {
        position: absolute;
        right: 12px;
        top: 10px;

        @apply focus:outline-none;

        span {
            @apply text-3xl;
        }
    }
}
</style>
