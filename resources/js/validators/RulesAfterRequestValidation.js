import validationHelper from '../helpers/validationHelper';

export default class RuleCategoriesAfterRequestValidation {
    constructor(data) {
        this.data = data;

        this.getAllNeedleColumns = [
            {
                name: 'category',
                type: 'string',
            },
            {
                name: 'content',
                type: 'string',
            },
        ];
    }

    validateGetAll() {
        const { validateColumns } = validationHelper();

        const { rules } = this.data;

        try {
            if (Array.isArray(rules) === false) {
                throw new Error('Rules field not required or not array');
            }

            rules.forEach((el) => {
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

    validateGetOne() {
        const { exists } = this.data;

        try {
            if (window.isBoolean(exists) === false) {
                throw new Error('Exists field not required or not boolean');
            }

            if (window.isString(this.data.content) === false && exists === true) {
                throw new Error('Content field not required or not string');
            }
        } catch (e) {
            console.log(e.message);
            return false;
        }

        return true;
    }
}
