import { reactive } from 'vue';

export default () => {
    const information = reactive({
        successful: '',
        notice: '',
        error: '',
    });

    const setDefaultInformation = () => {
        information.error = '';
        information.notice = '';
        information.successful = '';
    };

    const setSuccessful = (message = '') => {
        if (window.isString(message) === false) {
            return;
        }

        setDefaultInformation();
        information.successful = message;
    };

    const setNotice = (message = '') => {
        if (window.isString(message) === false) {
            return;
        }

        setDefaultInformation();
        information.notice = message;
    };

    const setError = (message = '') => {
        if (window.isString(message) === false) {
            return;
        }

        setDefaultInformation();
        information.error = message;
    };

    return {
        information,
        setDefaultInformation,
        setSuccessful,
        setNotice,
        setError,
    };
};
