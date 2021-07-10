export default () => {
    const closeAllEchoChannels = () => {
        const { channels } = window.echo.connector;

        Object.getOwnPropertyNames(channels).forEach((channel) => {
            window.echo.leave(channel);
        });
    };

    return { closeAllEchoChannels };
};
