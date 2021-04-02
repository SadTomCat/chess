export default () => {
    let interval;

    const countdown = (timeSeconds, callback) => {
        clearInterval(interval);

        if (timeSeconds < 1) {
            return;
        }

        interval = setInterval(() => {
            const seconds = timeSeconds % 60;
            const minutes = Math.floor((timeSeconds / 60) % 60);
            const hour = Math.floor(((timeSeconds / 60) / 60) % 60);

            if (timeSeconds < 0) {
                clearInterval(interval);
                return;
            }

            callback({
                hour,
                minutes,
                seconds,
            });
            timeSeconds--;
        }, 1000);
    };

    return { countdown };
};
