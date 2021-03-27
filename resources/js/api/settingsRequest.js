export default async (fields) => {
    const res = await window.axios.post('/settings', fields)
        .catch((e) => e.response);

    if (res.status !== 200 || !res.data.status) {
        if (res.data.errors === undefined && res.data.message !== undefined) {
            res.data.errors = res.data.message;
        }

        for (const [key, value] of Object.entries(res.data.errors)) {
            if (value instanceof Array) {
                res.data.errors[key] = value[0];
            }
        }

        return {
            status: false,
            errors: res.data.errors ?? 'What went wrong',
        };
    }

    return {
        status: true,
    };
};
