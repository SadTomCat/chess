import AfterPaginationRequestValidation from '../../validators/AfterPaginationRequestValidation';

export default async (userId, page) => {
    const res = await window.axios.get(`/api/users/${userId}/games/paginated?page=${page}`)
        .catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
        };
    }

    const validator = new AfterPaginationRequestValidation(res.data);

    if (validator.validate() === false) {
        return {
            status: false,
        };
    }

    return res.data;
};
