import validationHelper from '../helpers/validationHelper';

export default class RuleCategoriesAfterRequestValidation {
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
        ];
    }

    validateGetAll() {
        const { validateColumns } = validationHelper();

        const { categories } = this.data;

        try {
            if (Array.isArray(categories) === false) {
                throw new Error('Categories field not required or not array');
            }

            categories.forEach((el) => {
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
