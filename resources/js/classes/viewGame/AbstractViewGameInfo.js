import stringHelper from '../../helpers/stringHelper';

/**
 * @constructor
 * @param {Object} [info={}]
 *
 * @property {Object} formattedInfo
 * @property {Object} info
 * * */
export default class AbstractViewGameInfo {
    constructor(info = {}) {
        this.info = info;
        this.formattedInfo = {};
    }

    format() {
        this.formatInfoFields();
    }

    formatInfoFields() {
        const { upperFirstLetter } = stringHelper();

        Object.getOwnPropertyNames(this.info).forEach((el) => {
            const formattedKey = upperFirstLetter(el.replace(/_/g, ' '));

            this.formattedInfo[formattedKey] = this.info[el];
        });
    }

    getFormattedInfo() {
        return this.formattedInfo;
    }

    notNullOrUndefined(value) {
        return value !== undefined && value !== null;
    }
}
