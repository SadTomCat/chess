export default () => {
    const validateColumns = (req, needleColumns) => {
        try {
            needleColumns.forEach((needleColumn) => {
                if (req[needleColumn.name] === undefined) {
                    throw new Error(`${needleColumn.name} not exits`);
                }

                const typesValidators = {
                    string: window.isString,
                    number: window.isNumber,
                    boolean: window.isBoolean,
                    object: (val) => typeof val === 'object',
                    array: Array.isArray,
                };

                if (typesValidators[needleColumn.type] === undefined) {
                    throw new Error(`${needleColumn.type} cannot be checked`);
                }

                if (typesValidators[needleColumn.type](req[needleColumn.name]) === false) {
                    throw new Error(`${needleColumn.name} column got ${typeof req[needleColumn.name]} `
                        + `but must be ${needleColumn.type}`);
                }
            });
        } catch (e) {
            return {
                status: false,
                message: e.message,
            };
        }

        return { status: true };
    };

    return { validateColumns };
};
