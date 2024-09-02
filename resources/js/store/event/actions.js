
import { Http } from '../../config/axiosConfig.js';
import cript from '../../supports/cript.js';
import mutations from './mutations';

const actions = {
   async getBannersExent(flag) {
        try {
            const params = { flag: flag};
            const { data } = await Http.get('event-banners', { params });
            mutations.SET_EVENT_BANNERS(data.data);
        } catch (error) {
            console.error(error);
        }
    },

    async getEvent(flag) {
        // const hasEvent = localStorage.getItem('event');

        // if (hasEvent) {
        //     mutations.SET_EVENT(JSON.parse(cript.decript(hasEvent)));
        // }

        try {
            const params = { flag: flag};
            const { data } = await Http.get('event', { params });
            mutations.SET_EVENT(data.data);
        } catch (error) {
            console.error(error);
        }
    }
}

export default actions;