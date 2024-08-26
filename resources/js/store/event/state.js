import { ref } from "vue";

const state = ref({
    name: '',
    description: '',
    address: {},
    banners: [],
    attachments: [],
    address: {
        city: '',
        state: '',
        number: '',
        address: '',
        district: '',
        google_iframe: '',
        zip_code: '',
        instruction: ''
    }
});

export default state;