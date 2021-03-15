export default async (user) => {
    const res = await axios.post('/register', user).catch((err) => err.response);

    return res.data;
};
