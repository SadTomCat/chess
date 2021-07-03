import validationHelper from '../helpers/validationHelper';

export default class ChessRuleNamesAfterRequestValidation {
    constructor(data) {
        this.data = data;

        this.getAllNeedleColumns = [
            {
                name: 'id',
                type: 'number',
            },
            {
                name: 'name',
                type: 'string',
            },
            {
                name: 'slug',
                type: 'string',
            },
        ];
    }

    validateGetAll() {
        const { validateColumns } = validationHelper();

        const namesInfo = this.data.names_info;

        try {
            if (Array.isArray(namesInfo) === false) {
                throw new Error('Names info field not required or not array');
            }

            namesInfo.forEach((el) => {
                const validated = validateColumns(el, this.getAllNeedleColumns);

                if (validated.status === false) {
                    throw new Error(validated.message);
                }
            });
        } catch (e) {
            console.log(e.message);
            return false;
        }

        return true;
    }
}
