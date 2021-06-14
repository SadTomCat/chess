export default async (content, category) => {
    const data = { content };

    const res = await window.axios.patch(`/api/rules/${category}`, data)
        .catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
            message: res.data.message === undefined ? 'Something went wrong' : res.data.message,
        };
    }

    return res.data;
};
