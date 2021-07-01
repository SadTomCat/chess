export default async (user) => {
    const res = await window.axios.post('/register', user).catch((err) => err.response);

    return res.data;
};
