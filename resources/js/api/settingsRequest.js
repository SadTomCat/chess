export default async (fields) => {
    const res = await window.axios.post('/settings', fields)
        .catch((e) => e.response);

    if (res.status !== 200 || !res.data.status) {
        return {
            status: false,
            errors: res.data.errors ?? 'What went wrong',
        };
    }

    return {
        status: true,
    };
};
