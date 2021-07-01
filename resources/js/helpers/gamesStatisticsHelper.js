export default () => {
    const getWinRateAndLoseGames = (countGames, countWon, notCountGames) => {
        const loseGames = countGames - countWon - notCountGames;

        return {
            loseGames,
            winRate: (countWon / loseGames).toFixed(2),
        };
    };

    return {
        getWinRateAndLoseGames,
    };
};
