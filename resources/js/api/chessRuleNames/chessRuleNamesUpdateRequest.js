/**
 * Data is validated
 *
 * Successful {
 *      res.data - may contain unnecessary properties
 * }
 *
 * Fail {
 *     status: false,
 *     message: backend message | 'Something went wrong'
 * }
 * */
export default async (id, name) => {
    const res = await window.axios.patch(`/api/chess-rules/names/${id}`, { name })
        .catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
            message: res.data.message === undefined ? 'Something went wrong' : res.data.message,
        };
    }

    return res.data;
};
