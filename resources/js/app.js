require('./bootstrap');
import { createApp } from 'vue';
import Vue3Toasity from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const app = createApp();

import FormPublisherComponent from './views/admin/publishers/FormPublisherComponent.vue';

app.component('form-publisher-component', FormPublisherComponent);
app.use(Vue3Toasity, { autoClose: 3000 });

app.mount('#app');