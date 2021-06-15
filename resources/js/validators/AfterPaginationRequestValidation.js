import validationHelper from '../helpers/validationHelper';

export default class AfterPaginationRequestValidation {
    constructor(data) {
        this.data = data;

        this.needleColumns = [
            {
                name: 'items',
                type: 'array',
            },
            {
                name: 'current_page',
                type: 'number',
            },
            {
                name: 'last_page',
                type: 'number',
            },
            {
                name: 'total',
                type: 'number',
            },
        ];
    }

    validate() {
        const { validateColumns } = validationHelper();

        const columnsValidated = validateColumns(this.data, this.needleColumns);

        if (columnsValidated.status === false) {
            console.log(columnsValidated.message);

            return false;
        }

        return true;
    }
}
