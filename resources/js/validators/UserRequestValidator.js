import validationHelper from '../helpers/validationHelper';

export default class UserRequestValidator {
    constructor(data) {
        this.data = data;
    }

    validateGamesStatistics() {
        const { validateColumns } = validationHelper();

        const { statistics } = this.data;

        try {
            if (window.isObject(statistics) === false) {
                throw new Error('Statistics field not required or not array');
            }

            validateColumns(statistics, [
                {
                    name: 'count_games',
                    type: Number,
                },
                {
                    name: 'count_won',
                    type: Number,
                },
                {
                    name: 'not_count_games',
                    type: Number,
                },
            ]);
        } catch (e) {
            console.log(e.message);
            return false;
        }

        return true;
    }
}
