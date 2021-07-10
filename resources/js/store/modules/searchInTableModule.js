export default {
    state: () => ({
        selectedSearchColumns: [],
    }),

    mutations: {
        SET_SELECTED_COLUMNS: (state, payload) => {
            state.selectedSearchColumns.length = 0;

            payload.forEach((el) => {
                state.selectedSearchColumns.push(el);
            });
        },
    },
};
