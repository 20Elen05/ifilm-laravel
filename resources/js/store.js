import { createStore } from 'vuex';

const store = createStore({
    state() {
        return {
            lang: 'en',
        };
    },

    mutations: {
        setLang(state, lang) {
            state.lang = lang;
        },
    },

    actions: {
        updateLang(context, lang) {
            context.commit('setLang', lang);
        },
    },

    getters: {
        getLang(state) {
            return state.lang;
        },
    },
});

export default store;
