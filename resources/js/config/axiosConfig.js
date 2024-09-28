import Axios from "axios";

const apiUrl = "/api/v1/";

export const Http = Axios.create({
    baseURL: apiUrl
})