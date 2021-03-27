export default async (credentials) => {
    if (credentials.remember !== undefined && !credentials.remember) {
        delete credentials.remember;
    }

    const res = await window.axios.post('/login', credentials).catch((err) => err.response);

    return res.data;
};
