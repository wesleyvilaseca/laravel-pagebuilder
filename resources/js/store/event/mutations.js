import cript from "../../supports/cript";
import state from "./state";

const mutations = {
    SET_EVENT(payload) {
        state.value = payload;
        localStorage.setItem('event', cript.cript(JSON.stringify(state.value)));
    },

    SET_EVENT_BANNERS(payload) {
        state.value.banners = payload;
    }
}

export default mutations;