import AbstractViewGameInfo from './AbstractViewGameInfo';

/**
 * @see ViewGameGameInfo
 *
 * @constructor
 * @param {Object} [gameInfo={}]
 * @param {Object} [usersInfo={}]
 * @param {Number} [movesCount=0]
 * */
export default class ViewGameGameInfo extends AbstractViewGameInfo {
    constructor(gameInfo = {}, usersInfo = {}, movesCount = 0) {
        super(gameInfo);

        this.usersInfo = usersInfo;
        this.movesCount = movesCount;

        this.format();
    }

    format() {
        super.format();

        if (this.notNullOrUndefined(this.info.end_at) && this.notNullOrUndefined(this.info.start_at)) {
            const startAt = (new Date(this.info.start_at)).getTime() / 1000;
            const endAt = (new Date(this.info.end_at)).getTime() / 1000;

            const minAndSec = (endAt - startAt) / 60;
            this.formattedInfo.Duration = `${Math.floor(minAndSec)}m : ${(endAt - startAt) % 60}s`;
        }

        if (this.winnerExists()) {
            const userInfo = this.usersInfo[this.info.winner_color];

            if (userInfo !== undefined && userInfo.name !== undefined) {
                this.formattedInfo['Winner name'] = userInfo.name;
                this.formattedInfo['Count move'] = this.movesCount;
            }
        }
    }

    winnerExists() {
        return this.notNullOrUndefined(this.info.winner_color);
    }
}
