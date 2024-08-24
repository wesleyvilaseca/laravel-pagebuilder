import Axios from "axios";

const apiUrl = process.env.MIX_APP_URL +  "/api/v1/";

export const Http = Axios.create({
    baseURL: apiUrl
})