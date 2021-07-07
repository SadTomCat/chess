/*
                          Information
| ------------------------------------------------------------
|
|  1. This temporary file.
|  2. It need for JsDoc
|  3. In future this type migrate in typeScript
|
| ------------------------------------------------------------

*/

const needDebug = process.env.MIX_DEBUG;

const informAboutWrongType = (isValid, type, value, moreInfo = '') => {
    if (isValid === false && needDebug) {
        console.warn(`Is not ${type}`,
            '\nGiven value -', value,
            `\n${moreInfo}`);
    }
};

/**
 * @param {Function} typeValidator
 * @param {any} value
 * @param {?string} typeName
 * @return boolean
 * */
window.isArrayOf = (typeValidator, value, typeName = 'needle type') => {
    let isValid = true;

    if (Array.isArray(value) === false) {
        informAboutWrongType(isValid, 'isChessRuleNamesInfo', value, 'It not Array');
        return false;
    }

    for (let i = 0; i < value.length; i++) {
        if (typeValidator(value[i]) === false) {
            isValid = false;
            break;
        }
    }

    informAboutWrongType(isValid, typeName, value);

    return isValid;
};

/**
 * @typedef ChessRuleNameInfo
 * @type Object
 * @property {?(String|Number)} id
 * @property {String} name
 * @property {String} slug
 * */
window.isChessRuleNameInfo = (value) => {
    try {
        if (window.isObject(value) === false) {
            throw Error('Is not object');
        }

        /** value.id !== undefined because is optional property */
        if (value.id !== undefined && window.isNumber(value.id) === false && window.isString(value.id) === false) {
            throw Error('Invalid id');
        }

        if (window.isString(value.name) === false || window.isString(value.slug) === false) {
            throw Error('Invalid name or slug');
        }
    } catch (e) {
        informAboutWrongType(false, 'isChessRuleNameInfo', value, e.message);
        return false;
    }

    return true;
};

/**
 * @typedef ChessRuleLink extend isChessRuleNameInfo
 * @type Object
 * @property {?(String|Number)} id
 * @property {String} name
 * @property {String} slug
 * @property {String} link
 * */
window.isChessRuleLink = (value) => {
    const isValid = window.isChessRuleNameInfo(value) && window.isString(value.link);

    informAboutWrongType(isValid, 'isChessRuleLink', value);

    return isValid;
};

/**
 * @typedef UrlLink
 * @type Object
 * @property {String|Object} path
 * @property {String} name
 * @property {?String} icon
 * */
window.isUrlLink = (value) => {
    const isPathObject = (path) => window.isObject(path) === true && window.isString(path.name);

    try {
        if (window.isObject(value) === false) {
            throw Error('Is not object');
        }

        if (window.isString(value.path) === false && isPathObject(value.path) === false) {
            throw Error('Invalid path');
        }

        if (window.isString(value.name) === false) {
            throw Error('Invalid name');
        }

        if (value.icon !== undefined && window.isString(value.icon) === false) {
            throw Error('Invalid icon');
        }
    } catch (e) {
        informAboutWrongType(false, 'isLink', value, e.message);
        return false;
    }

    return true;
};
