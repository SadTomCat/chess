export default () => {
    const closeAllEchoChannels = () => {
        const { channels } = echo.connector;

        for (const channel in channels) {
            echo.leave(channel);
        }
    };

    return { closeAllEchoChannels };
};
