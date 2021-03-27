export default async () => {
    const res = await window.axios.post('/logout').catch((err) => err.response);

    return res.status === 200;
};
