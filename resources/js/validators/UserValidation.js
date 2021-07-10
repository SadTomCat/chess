/**
 * Not complete
 * */
export default class UserValidation {
    /**
     * @param {string} name
     * @param {string} password
     * @param {string} email
     * @param {string} confPassword
     * @param {boolean} needConfPasswordValidation
     * */
    constructor(name, email, password, confPassword = '', needConfPasswordValidation = false) {
        this.reset(name, email, password, confPassword, needConfPasswordValidation);
    }

    /**
     * @param {string} name
     * @param {string} password
     * @param {string} email
     * @param {string} confPassword
     * @param {boolean} needConfPasswordValidation
     * @return UserValidation
     * */
    reset(name, email, password, confPassword = '', needConfPasswordValidation = false) {
        this.name = name;
        this.password = password;
        this.email = email;
        this.confPassword = confPassword;
        this.needConfPasswordValidation = needConfPasswordValidation;
        this.errors = {};

        return this;
    }

    /**
     * @return boolean
     * */
    validate() {
        this.errors = {};
        let status = true;

        if (UserValidation.canUseName(this.name) === false) {
            this.errors.name = UserValidation.getNameErrorMessage();
            status = false;
        }

        if (UserValidation.canUseEmail(this.email) === false) {
            this.errors.email = UserValidation.getEmailErrorMessage();
            status = false;
        }

        if (UserValidation.canUsePassword(this.password) === false) {
            this.errors.password = UserValidation.getPasswordErrorMessage();
            status = false;
        }

        if (this.needConfPasswordValidation === true
            && (UserValidation.canUsePassword(this.confPassword) === false || this.password !== this.confPassword)) {
            status = false;

            this.passwordConfirmation = this.password === this.confPassword
                ? 'Invalid confirmation password'
                : 'Password and confirmation password not same';
        }

        return status;
    }

    /**
     * @return string[]
     * */
    getErrors() {
        return this.errors;
    }

    /**
     * @return boolean
     * @param name
     * */
    static canUseName(name) {
        return window.isString(name) === true && name.length >= 3 && name.length <= 255;
    }

    /**
     * @param {string} password
     * @return boolean
     * */
    static canUsePassword(password) {
        return window.isString(password) === true && password.length >= 8 && password.length <= 32;
    }

    /**
     * @param {string} email
     * @return boolean
     * */
    static canUseEmail(email) {
        // eslint-disable-next-line max-len
        const re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;

        return re.test(email);
    }

    /**
     * @return string
     * */
    static getPasswordErrorMessage() {
        return 'Invalid password. Should be more than 7 characters and less than 33 characters';
    }

    /**
     * @return string
     * */
    static getNameErrorMessage() {
        return 'Invalid name. Should be more than 3 characters and less than 256 characters';
    }

    /**
     * @return string
     * */
    static getEmailErrorMessage() {
        return 'Invalid email';
    }
}
