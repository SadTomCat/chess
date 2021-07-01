export default async (email) => {
    const res = await window.axios.post('/forgot-password', { email })
        .catch((e) => e.response);

    if (!res.data.status) {
        return {
            status: false,
            message: res.data.errors ?? 'Something went wrong. Maybe a link has already been sent.',
        };
    }

    return {
        status: true,
        message: 'Link was sent. Check you email',
    };
};
