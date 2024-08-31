
import { Http } from '../../config/axiosConfig.js';
import mutations from './mutations';

const actions = {
   async getPublishers(params) {
        try {
            const { data } = await Http.get('event-publishers', { params });
            mutations.SET_PUBLISHERS(data, params.filter);
        } catch (error) {
            console.error(error);
        }
    },

    async getPublisher(params) {
        try {
            const { data } = await Http.get('event-publisher', { params });
            mutations.SET_PUBLISHER(data.data);
        } catch (error) {
            console.error(error);
        }
    }
}

export default actions;