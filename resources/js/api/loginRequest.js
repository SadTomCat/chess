export default async (credentials) => {
    if (!credentials.remember) {
        delete credentials.remember;
    }

    const res = await axios.post('/login', credentials).catch((err) => err.response);

    return res.data;
};
