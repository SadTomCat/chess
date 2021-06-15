export default () => {
    const validateColumns = (data, needleColumns) => {
        try {
            needleColumns.forEach((needleColumn) => {
                if (data[needleColumn.name] === undefined) {
                    throw new Error(`${needleColumn.name} not exits`);
                }

                const typesValidators = {
                    string: window.isString,
                    number: window.isNumber,
                    boolean: window.isBoolean,
                    object: window.isObject,
                    array: Array.isArray,
                };

                if (typesValidators[needleColumn.type] === undefined) {
                    throw new Error(`${needleColumn.type} cannot be checked`);
                }

                if (typesValidators[needleColumn.type](data[needleColumn.name]) === false) {
                    throw new Error(`${needleColumn.name} column got ${typeof data[needleColumn.name]} `
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
