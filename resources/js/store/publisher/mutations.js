import cript from "../../supports/cript";
import state from "./state";

const mutations = {
    SET_PUBLISHERS(payload, filter = null) {
        const getProxy = (data) => {
            return new Proxy(data, {
                get(target, prop, receiver) {
                    return target[prop]
                }
            });
        }

        var publishers;
        var newPublishers;
        if(state.value.filter == filter) {
            publishers = getProxy(state.value.data);
            newPublishers = getProxy(payload.data);
        } else {
            publishers = [];
            newPublishers = getProxy(payload.data);
        }

        const mergedObject = [...publishers, ...newPublishers]
        state.value.data = mergedObject;
        
        const meta = payload.meta;
        var next_page = meta.current_page + 1
        state.value.meta = {
            prev_page: meta.from,
            current_page: meta.current_page,
            per_page: meta.per_page,
            total: meta.total,
            next_page: meta.current_page == meta.last_page ? null : next_page
        }

        state.value.filter = filter;
    }
}

export default mutations;