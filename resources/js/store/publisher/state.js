import { ref } from "vue";

const state = ref({
    filter: '',
    data: [],
    meta: {
        prev_page: "",
        current_page: "",
        per_page: "",
        total: "",
        next_page: ""
    }
});

export default state;