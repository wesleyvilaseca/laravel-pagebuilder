import state from "./state";

const mutations = {
    SET_PUBLISHER(payload) {
        state.value.publisher = payload;
    },

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
            publishers = getProxy(state.value.publishers.data);
            newPublishers = getProxy(payload.data);
        } else {
            publishers = [];
            newPublishers = getProxy(payload.data);
        }

        const mergedObject = [...publishers, ...newPublishers]
        state.value.publishers.data = mergedObject;
        
        const meta = payload.meta;
        var next_page = meta.current_page + 1
        state.value.publishers.meta = {
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