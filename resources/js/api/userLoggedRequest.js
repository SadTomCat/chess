export default async () => {
    const res = await window.axios.post('/user-logged').catch((err) => err.response);

    if (res.status !== 200 || !res.data.status) {
        return false;
    }

    const { user } = res.data;
    user.logged = true;

    return user;
};
