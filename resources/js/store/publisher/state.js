import { ref } from "vue";

const state = ref({
    filter: '',
    publisher: {
        name: '',
        description:'',
        url: '',
        logo: '',
        site: '',
        email: '',
        price_list: '',
        address: {
            city: '',
            state: '',
            number: null,
            address: '',
            district: '',
            zip_code: ''
        },
        social: {
            youtube: null,
            facebook: '',
            instagram: ''
        }
    },
    publishers: {
        data: [],
        meta: {
            prev_page: "",
            current_page: "",
            per_page: "",
            total: "",
            next_page: ""
        }
    }
});

export default state;