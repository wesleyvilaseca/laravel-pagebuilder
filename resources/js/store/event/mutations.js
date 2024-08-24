import state from "./state";

const mutations = {
    SET_EVENT(payload) {
        state.value = payload;
    },

    SET_EVENT_BANNERS(payload) {
        state.value.banners = payload;
    }
}

export default mutations;