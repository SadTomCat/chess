import AbstractViewGameInfo from './AbstractViewGameInfo';

/**
 * @see ViewGameUserInfo
 *
 * @constructor
 * @param {Object} [userInfo={}]
 * @param {String} [ownColor='']
 * @param {String} [winnerColor='']
 * */
export default class ViewGameUserInfo extends AbstractViewGameInfo {
    constructor(userInfo = {}, ownColor = '', winnerColor = '') {
        super(userInfo);
        this.winnerColor = winnerColor;
        this.ownColor = ownColor;

        this.format();
    }

    format() {
        super.format();

        if (this.info.count_won !== undefined && this.info.count_games !== undefined) {
            const countLose = this.info.count_games - this.info.count_won;
            this.formattedInfo['Count lose'] = countLose;
            this.formattedInfo['Win rate'] = (this.info.count_won / countLose).toFixed(2);
        }

        if (this.ownColor !== '') {
            this.formattedInfo['Chessman color'] = this.ownColor;
        }
    }

    /**
     * @return Number|String
     * */
    getUserId() {
        return (window.isNumber(this.info.id) === true || window.isString(this.info.id) === true) ? this.info.id : 0;
    }

    getUserName() {
        return this.info.name ?? '';
    }

    isWinner() {
        return this.ownColor === this.winnerColor;
    }
}
